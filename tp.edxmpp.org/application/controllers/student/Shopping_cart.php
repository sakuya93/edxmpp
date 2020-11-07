<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class shopping_cart extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("shopping_cart_model", "Model", TRUE);
    }

    function ecpayCheckMacValue(array $params, $hashKey, $hashIV, $encType = 1)
    {
        // 0) 如果資料中有 null，必需轉成空字串
        $params = array_map('strval', $params);

        // 1) 如果資料中有 CheckMacValue 必需先移除
        unset($params['CheckMacValue']);

        // 2) 將鍵值由 A-Z 排序
        uksort($params, 'strcasecmp');

        // 3) 將陣列轉為 query 字串
        $paramsString = urldecode(http_build_query($params));

        // 4) 最前方加入 HashKey，最後方加入 HashIV
        $paramsString = "HashKey={$hashKey}&{$paramsString}&HashIV={$hashIV}";

        // 5) 做 URLEncode
        $paramsString = urlencode($paramsString);

        // 6) 轉為全小寫
        $paramsString = strtolower($paramsString);

        // 7) 轉換特定字元
        $paramsString = str_replace('%2d', '-', $paramsString);
        $paramsString = str_replace('%5f', '_', $paramsString);
        $paramsString = str_replace('%2e', '.', $paramsString);
        $paramsString = str_replace('%21', '!', $paramsString);
        $paramsString = str_replace('%2a', '*', $paramsString);
        $paramsString = str_replace('%28', '(', $paramsString);
        $paramsString = str_replace('%29', ')', $paramsString);

        // 8) 進行編碼
        $paramsString = $encType ? hash('sha256', $paramsString) : md5($paramsString);

        // 9) 轉為全大寫後回傳
        return strtoupper($paramsString);
    }

    public function ecpayCheck()
    {
        $this->db->trans_begin();
        $data = $this->input->post();

        $key = $this->ecpayCheckMacValue($data, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS', 1);
        if ($key == $data['CheckMacValue']) {
            //此部分為取得平台目前抽成
            $drawInto = $this->Model->getDrawInto()->draw_into; //抽成%數

            $TNO = $this->Model->getTNO($data['MerchantTradeNo']);
            $TNO = mb_split("、", $TNO);
            $update = array();
            $teacherIncome = array();
            foreach ($TNO as $temp) {
                $shoppingData = $this->Model->getShoppingCartData($temp,$drawInto);
                if ($shoppingData == null)
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=2");
                $update[] = array(
                    'sc_id' => $temp,
                    'sc_payStatus' => '1',
                );

                $bo = true;

                foreach ($teacherIncome AS $tKey => $teacherTncomeTemp) {
                    if ($shoppingData->t_id == $teacherTncomeTemp['t_id']) {
                        $bo = false;
                        $teacherIncome[$tKey]['price'] += $shoppingData->price;
                    }
                }
                if ($bo) {
                    $teacherIncome[] = array(
                        't_id' => $shoppingData->t_id,
                        'price' => $shoppingData->price
                    );
                }
            }


            if (!$this->Model->updateTeacherIncome($teacherIncome))
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=2");
            if (!$this->Model->updatePayStatus($update))
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=2");
            if (!$this->Model->updatePaymentHistory($data['MerchantTradeNo']))
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=2");

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=2");
                return;
            } else {
                $this->db->trans_commit();
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=1");
                return;
            }
        } else
            var_dump("3");
        header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");

    }

    public
    function index()
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();
            //初始化變數
            $HTML = array(
                'content' => '',
                'detail' => '',
            );
            $price = array(
                'price' => 0
            );
            //初始化變數結束

            if (!$this->check_memberData()) {
                $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $HTML['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="modify_member_information">帳號設定</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $dataArray = $this->Model->shoppingRecord();

            $HTML['totalPrice'] = 0;
            if (count($dataArray) == 0) {
                $HTML['content'] .= "<h3 style='padding:20px 15px;font-size: 24px;color: grey'>購物車暫無商品!</h3>";
            } else {
                for ($i = 0; $i < count($dataArray); $i++) {
                    /*  堂數/單價資料載入START  */
                    //該筆堂數資料
                    $number_of_courses_content = "";
                    $unit_price = 0;
                    $currency = "";
                    $HTML['totalPrice'] += str_replace(",", "", $this->Model->getCoursePriceTWD($dataArray[$i]->cf_id, $dataArray[$i]->sc_id));

                    if ($dataArray[$i]->l_id != null) {

                        $NOL_data = $this->Model->getNumberOfLessonsConfig($dataArray[$i]->l_id);
                        for ($j = 0; $j < count($NOL_data); $j++) {
                            if ($NOL_data[$j]->cd_number == $dataArray[$i]->sc_NumberOfLessons) {
                                $unit_price = $NOL_data[$j]->cd_discountedPrices;
                                $currency = $NOL_data[$j]->cd_currency;
                                $number_of_courses_content .= "
                                <option value=\"{$NOL_data[$j] -> cd_number} 堂{$NOL_data[$j] -> cd_currency}$ {$NOL_data[$j] -> cd_discountedPrices}\" selected>{$NOL_data[$j] -> cd_number} 堂{$NOL_data[$j] -> cd_currency}$ {$NOL_data[$j] -> cd_discountedPrices}</option>
                            ";
                            } else {
                                $number_of_courses_content .= "
                               <option value=\"{$NOL_data[$j] -> cd_number} 堂{$NOL_data[$j] -> cd_currency}$ {$NOL_data[$j] -> cd_discountedPrices}\">{$NOL_data[$j] -> cd_number} 堂{$NOL_data[$j] -> cd_currency}$ {$NOL_data[$j] -> cd_discountedPrices}</option>
                            ";
                            }
                        }
                        $classPath = "Teacher_sales/live/{$dataArray[$i]->l_id}";
                    } else {
                        $filmData = $this->Model->getFilmData($dataArray[$i]->cf_id);
                        $unit_price = $filmData->cf_price;
                        $currency = $filmData->cf_currency;
                        $number_of_courses_content .= "<option value=\"{$filmData->cf_currency}$ {$filmData->cf_price}元\" selected>{$filmData->cf_currency}$ {$filmData->cf_price}元</option>";
                        $dataArray[$i]->hours = $filmData->cf_hours;
                        //$dataArray[$i]->sc_id = $filmData->cf_id;
                        $classPath = "film_courses/$filmData->cf_id";
                    }
                    /*  堂數/單價資料載入END  */

                    $HTML['content'] .= "
                <div class=\"internal\">
                    <div class='ml-10'>
                        <div class='shopping_cart-data' style='display: none'>{$dataArray[$i]->sc_id}</div>
                        <input class='form-check-input check_box-data' id = \"check_box{$i}\" type='checkbox' value='' checked>
                    </div>
                <img class=\"sticker col-sm-4\"
                     src=\"resource/image/student/photo/{$dataArray[$i]->photo}\"
                     onclick=\"window.location='$classPath'\">
                <div class=\"news_area\">
                    <b><span class=\"name\">{$dataArray[$i]->t_name}</span></b>
                    <br>
                    <div class=\"t_name\">
                        <span onclick=\"window.location = '$classPath'\">{$dataArray[$i]->sc_className}</span>
                    </div>
                    <br>
                    <div class=\"introduction\">
                        <b>來自</b> <span class=\"from\">{$dataArray[$i]->country}</span> <br>
                        <b>會說</b> <span class=\"speak\">{$dataArray[$i]->speakLanguage}</span>
                    </div>
                </div>

                <div class=\"learn_area\">
                    <span class='phone_learn_title'>學習</span>
                    <select class=\"custom-select learn\" disabled>
                        <option value=\"{$dataArray[$i]->sc_className}\" selected>{$dataArray[$i]->sc_className}</option>
                    </select>
                </div>

                <div class=\"hour_area\">
                    <span class='phone_class_hours_title'>時間</span>
                    <span id=\"hour\">{$dataArray[$i]->hours}小時</span>
                </div>

                <div class=\"purchase_course_area\">
                    <span class='phone_course_amount_title'>課程金額</span>
                    <select class=\"custom-select purchase_course shopping_cart-data\" onchange=\"change_classes(this.value, {$i})\" disabled>
                        {$number_of_courses_content}
                    </select>
                </div>

                <div class=\"delete_commodity\">
                    <i class=\"fa fa-times-circle\" onclick=\"window.location = 'shopping_cart/deleteShopping/{$dataArray[$i]->sc_id}'\"></i>
                </div>
            </div>";

                    if ($i < count($dataArray) - 1) { //加分隔線
                        $HTML['content'] .= "<div class=\"separation_line\"></div>";
                    }
                    if ($dataArray[$i]->l_id != null) {

                    } else {

                    }
                    $HTML['detail'] .= "<div class='detail_show' id=\"detail{$i}\"><div class=\"name\">
                        <span>{$dataArray[$i]->sc_className}</span>
                    </div>
                    <div>
                        <span id=\"currency{$i}\">{$currency} $</span><span class=\"unit_price\" id=\"price{$i}\">{$unit_price}</span>
                    </div></div>";
                }
            }
            $HTML['diamond'] = $HTML['totalPrice'] * 3;
            $nav = $this->get_nav();
            $name = $nav->name; //導覽列使用者名稱
            $HTML['name'] = $name != '' ? $name : 'XXX';

            $photo = $nav->photo; //導覽列頭像
            $HTML['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//			$HTML['classOption'] = $this->getClassOption();
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');


            $this->load->view('student/shopping_cart_view', $HTML);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }


    public
    function addShopping($type = '', $id = '', $nol = '')
    {
        $this->checkOneLogin();
        if ($type == 'live') {
            if ($this->Model->checkLiveIsMy($id) == 1) {
                redirect(base_url('shopping_cart'));
            }
            $liveData = $this->Model->get_liveData($id);
            if ($liveData == null) {
                redirect(base_url("Teacher_sales/live/{$id}"));
                return;
            }
            $insert = array(
                'sc_id' => uniqid(),
                'l_id' => $id,
                'm_id' => $_SESSION['Mid'],
                't_id' => $liveData->t_id,
                'sc_className' => $liveData->l_actualMovie,
                'sc_payStatus' => 0,
                'sc_NumberOfLessons' => $nol,
                'sc_date' => date('Y-m-d H:i:s'),
            );
            if ($this->Model->addShopping($insert)) {
//                echo json_encode(array('status' => true, 'msg' => '以新增到購物車中'));
                redirect(base_url('shopping_cart'));
            } else {
//                echo json_encode(array('status' => false, 'msg' => '無法將此課程增加到購物車，請重新嘗試'));
                redirect(base_url("Teacher_sales/live/{$id}"));
            }
        } else if ($type == 'film') {
            if ($this->Model->checkFilmIsMy($id) == 1) {
                redirect(base_url('shopping_cart'));
            }
            $filmData = $this->Model->get_filmData($id);
            if ($filmData == null) {
                redirect(base_url("film_courses/{$id}"));
                return;
            }
            $insert = array(
                'sc_id' => uniqid(),
                'cf_id' => $id,
                'm_id' => $_SESSION['Mid'],
                't_id' => $filmData->t_id,
                'sc_className' => $filmData->cf_name,
                'sc_payStatus' => 0,
                'sc_price' => $filmData->cf_price,
                'sc_date' => date('Y-m-d H:i:s'),
            );
            if ($this->Model->addShopping($insert)) {
//                echo json_encode(array('status' => true, 'msg' => '以新增到購物車中'));
                redirect(base_url('shopping_cart'));
            } else {
//                echo json_encode(array('status' => false, 'msg' => '無法將此課程增加到購物車，請重新嘗試'));
                redirect(base_url("film_courses/{$id}"));
            }
        }
    }

    public function shoppingBuyClass()
    {
        $this->db->trans_begin();
        if (!$this->check_memberData() | !$this->getEmailStatus()) { //如未設定基本資料及驗證信箱時
            header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
            return;
        }

        $this->checkOneLogin();
        date_default_timezone_set('Asia/Taipei');
        $data = array();
        $Data = $this->input->post();

        foreach ($Data['sc'] as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
        );
        $this->load->library('my_ecpay');
        try {
            $obj = $this->my_ecpay->load(); //用前述建立的library來取得ECPay_AllInOne物件
            //服務參數，最重要的是前四項，正式刷卡與測試刷卡時使用的服務位置會不同，後續三個參數是當你已經向綠界申請好會員後，可以在會員管理後台找到的自己的商店代號
            $obj->ServiceURL = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
            $obj->HashKey = "5294y06JbISpM5x9"; //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV = "v77hoKGq4kWxNNIS"; //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID = "2000132";   //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = "1";    //CheckMacValue加密類型，請固定填入1，使用SHA256加密
            $obj->Send['ReturnURL'] = "https://ajcode.tk/teaching_platform_dev/home";    //付款完成通知回傳的網址(頁面B)
            $obj->Send['ClientBackURL'] = "https://ajcode.tk/teaching_platform_dev/home";    //提供一個可以連回網站頁面的按鈕(頁面D)
            $obj->SendExtend['CreditInstallment'] = '';    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
            $obj->SendExtend['InstallmentAmount'] = 0;    //使用刷卡分期的付款金額，預設0(不分期)
            $obj->SendExtend['Redeem'] = false;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "TNO" . time(); //付款的訂單編號，這邊用時間戳記來當作編號，避免重複
            $ph_id = $this->Model->getTid();

            $obj->Send['MerchantTradeNo'] = $MerchantTradeNo;      //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');   //交易時間
            $obj->Send['TradeDesc'] = "這是一筆測試交易";                          //交易描述
            $pay_way = 'Credit';
            switch ($pay_way) { //你可以用一個參數來決定客戶是用什麼方式付款
                case "Credit":
                    $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL; //付款方式:Credit
                    $obj->Send['OrderResultURL'] = "https://ajcode.tk/teaching_platform_dev/shopping_cart/ecpayCheck"; //付款完成通知回傳的網址，客戶會被導回此頁面(頁面C)
                    break;
                case "ATM":
                    $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ATM; //付款方式:ATM
                    $obj->Send['ExpireDate'] = 3; //用ATM付款的話，可以設定要求客戶要在幾天內完成付款
                    //$obj->Send['ClientBackURL'] = "https://ajcode.tk/teaching_platform_dev/shopping_cart"; //付款完成通知回傳的網址，客戶會被導回此頁面(頁面C)
                    break;
            }

            //$obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ; //不使用付款方式:GooglePay
            //可以傳遞一些自己的資訊給綠界，這些資訊會被記錄在刷卡紀錄中，最多可以傳遞4個自定義參數，但參數的key值不能自訂，算是有點不方便
            $obj->Send['CustomField1'] = "AAAAAAAAAAAAA";
            $obj->Send['CustomField2'] = "BBBBBBBBBBBBB";
            $project = '';
            $price = 0;

            foreach ($data as $temp) {
                $Form_normalization = $this->Form_normalization($temp, $config);
                if (!$Form_normalization->type) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }

                if ($temp['NumberOfLessons'] != 0) {
                    $courseData = $this->Model->getLivePrice($temp['id']);
                    if ($courseData == null) {
                        header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                        return;
                    }
                    $project .= "{$temp['id']}、";
                    $price = $price + (int)$courseData->price;

                } else {
                    $courseData = $this->Model->getFilmPrice($temp['id']);
                    if ($courseData == null) {
                        header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                        return;
                    }
                    $project .= "{$temp['id']}、";
                    $price = $price + (int)$courseData->price;
                }
                array_push($obj->Send['Items'],
                    array('Name' => $courseData->courseName, 'Price' => (int)$courseData->price, 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => "dedwed")
                );
            }

            //此部分為取得平台目前抽成
            $drawInto = $this->Model->getDrawInto()->draw_into; //抽成%數
            $platformDraw = ceil(($price * ($drawInto / 100))); //平台抽成價格; ceil => 小數點無條件進位

            if ($temp['payMod'] == '0') {
                $insert = array(
                    'ph_id' => $MerchantTradeNo,
                    'm_id' => $_SESSION['Mid'],
                    'ph_project' => substr($project, 0, -3),
                    'ph_price' => $price - $platformDraw, //課程總價錢再扣除平台抽成價錢
                    'ph_drawInto' => $platformDraw,
                    'ph_scale' => $drawInto,
                    'ph_status' => '0',
                    'ph_date' => date('Y-m-d H:i:s')
                );
            } elseif ($temp['payMod'] == '1') {
                $insert = array(
                    'ph_id' => $MerchantTradeNo,
                    'm_id' => $_SESSION['Mid'],
                    'ph_project' => substr($project, 0, -3),
                    'ph_price' => $price - $platformDraw, //課程總價錢再扣除平台抽成價錢
                    'ph_drawInto' => $platformDraw,
                    'ph_scale' => $drawInto,
                    'ph_status' => '1',
                    'ph_date' => date('Y-m-d H:i:s')
                );
                $TNO = mb_split("、", $insert['ph_project']);
                $update = array();
                $teacherIncome = array();
                foreach ($TNO as $temp) {
                    $shoppingData = $this->Model->getShoppingCartData($temp,$drawInto);
                    if ($shoppingData == null)
                        header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    $update[] = array(
                        'sc_id' => $temp,
                        'sc_payStatus' => '1',
                    );

                    $bo = true;

                    foreach ($teacherIncome AS $tKey => $teacherTncomeTemp) {
                        if ($shoppingData->t_id == $teacherTncomeTemp['t_id']) {
                            $bo = false;
                            $teacherIncome[$tKey]['price'] += $shoppingData->price;
                        }
                    }
                    if ($bo) {
                        $teacherIncome[] = array(
                            't_id' => $shoppingData->t_id,
                            'price' => $shoppingData->price
                        );
                    }
                }

                if ($this->Model->checkDiamond($price) == 0) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }
                if (!$this->Model->shoppingBuyClass($insert, $ph_id)) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }
                if (!$this->Model->deductionDiamond($insert)) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }

                if (!$this->Model->updateTeacherIncome($teacherIncome)) { //老師薪資
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }
                if (!$this->Model->updatePayStatus($update)) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }
                if (!$this->Model->updatePaymentHistory($MerchantTradeNo)) {
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }
                if (!$this->Model->addEarned_amount($platformDraw)) { //平台抽成總價錢累加
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                }

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                    return;
                } else {
                    $this->db->trans_commit();
                    header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=1");
                    return;
                }
            }
            $obj->Send['TotalAmount'] = $insert['ph_price'];    //交易金額，刷卡時需付款的實際金額數值
            $obj->Send['TotalAmount'] = $price;    //交易金額，刷卡時需付款的實際金額數值
            if (!$this->Model->shoppingBuyClass($insert, $ph_id)) {
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                return;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                header("Location: https://ajcode.tk/teaching_platform_dev/shopping_cart?status=3");
                return;
            } else {

                $this->db->trans_commit();
                $obj->CheckOut();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public
    function deleteShopping($id = '')
    {
        $this->checkOneLogin();
        if ($this->Model->deleteShopping($id))
//            echo json_encode(array('status' => true, 'msg' => '刪除成功'));
            redirect(base_url('shopping_cart'));
        else
//            echo json_encode(array('status' => false, 'msg' => '刪除失敗'));
            redirect(base_url('error'));
    }
}

