<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stored_value extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("stored_value_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();

            if (!$this->check_memberData() | !$this->getEmailStatus()) {
                $data['become_teacher_link'] = '<a class="nav-link" id="test" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data['course_management_link'] = '<a class="nav-link" href="modify_member_information">帳號設定</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" style="float:left;" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;

            $data['name'] = $name != '' ? $name : 'XXX';
            $data['photo_path'] = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';
//            $data['classOption'] = $this->getClassOption();
            $data['RightInformationColumn'] = $this->getRightInformationColumn('', $data['photo_path'], $data['name']);
            $data['headerRightBar'] = $this->getHeaderRightBar('', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
            $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');


            $this->load->view('student/stored_value_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function storedValueSend()
    {
        $point = array(
            "100" => "300",
            "250" => "750",
            "500" => "1500",
            '1000' => '3000',
            '2500' => '7500',
        );
        $data = $this->input->post();
        if (!isset($point["{$data['price']}"])) {
            echo json_encode(array('status' => false, 'msg' => '無此價格選項，請刷新頁面重新嘗試'));
            return;
        }
        if ($this->Model->checkMemberEmailStatus() != 1) {
            echo json_encode(array('status' => false, 'msg' => '請先完成信箱認證在儲值'));
            return;
        }
        $this->load->library('my_ecpay');
        try {
            $obj = $this->my_ecpay->load(); //用前述建立的library來取得ECPay_AllInOne物件
            //服務參數，最重要的是前四項，正式刷卡與測試刷卡時使用的服務位置會不同，後續三個參數是當你已經向綠界申請好會員後，可以在會員管理後台找到的自己的商店代號
            $obj->ServiceURL = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
            $obj->HashKey = "5294y06JbISpM5x9"; //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV = "v77hoKGq4kWxNNIS"; //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID = "2000132";   //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = "1";    //CheckMacValue加密類型，請固定填入1，使用SHA256加密
            $obj->Send['ReturnURL'] = "https://tp.edxmpp.org/home";    //付款完成通知回傳的網址(頁面B)
            $obj->Send['ClientBackURL'] = "https://tp.edxmpp.org/home";    //提供一個可以連回網站頁面的按鈕(頁面D)
            $obj->SendExtend['CreditInstallment'] = '';    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
            $obj->SendExtend['InstallmentAmount'] = 0;    //使用刷卡分期的付款金額，預設0(不分期)
            $obj->SendExtend['Redeem'] = false;           //是否使用紅利折抵，預設false
            $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "TNO" . time(); //付款的訂單編號，這邊用時間戳記來當作編號，避免重複

            $obj->Send['MerchantTradeNo'] = $MerchantTradeNo;      //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');   //交易時間
            $obj->Send['TradeDesc'] = "這是一筆測試交易";                          //交易描述
            $pay_way = 'Credit';
            switch ($pay_way) { //你可以用一個參數來決定客戶是用什麼方式付款
                case "Credit":
                    $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL; //付款方式:Credit
                    $obj->Send['OrderResultURL'] = "https://tp.edxmpp.org/stored_value/ecpayCheck"; //付款完成通知回傳的網址，客戶會被導回此頁面(頁面C)
                    break;
                case "ATM":
                    $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::ATM; //付款方式:ATM
                    $obj->Send['ExpireDate'] = 3; //用ATM付款的話，可以設定要求客戶要在幾天內完成付款
                    //$obj->Send['ClientBackURL'] = "https://ajcode.tk/teaching_platform_dev/shopping_cart"; //付款完成通知回傳的網址，客戶會被導回此頁面(頁面C)
                    break;
            }

            //$obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ; //不使用付款方式:GooglePay
            //可以傳遞一些自己的資訊給綠界，這些資訊會被記錄在刷卡紀錄中，最多可以傳遞4個自定義參數，但參數的key值不能自訂，算是有點不方便

            array_push($obj->Send['Items'],
                array('Name' => "鑽石{$point["{$data['price']}"]}點", 'Price' => (int)$data['price'], 'Currency' => "元", 'Quantity' => (int)"1", 'URL' => "dedwed")
            );


            $insert = array(
                'ph_id' => $MerchantTradeNo,
                'm_id' => $_SESSION['Mid'],
                'ph_project' => 'point',
                'ph_price' => $data['price'],
                'ph_status' => '0',
                'ph_date' => date('Y-m-d H:i:s')
            );
            $obj->Send['TotalAmount'] = $data['price'];    //交易金額，刷卡時需付款的實際金額數值

            if ($this->Model->storedValueSend($insert, $this->Model->getOldOrder())) {
                $obj->CheckOut();
            } else
                echo json_encode(array('status' => false, 'msg' => '購買失敗，請重新嘗試'));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        $data = $this->input->post();
        if ($data == null)
            header("Location: https://tp.edxmpp.org/stored_value?status=4");
        $point = array(
            "100" => "300",
            "250" => "750",
            "500" => "1500",
            '1000' => '3000',
            '2500' => '7500',
        );

        $key = $this->ecpayCheckMacValue($data, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS', 1);
        if ($key == $data['CheckMacValue']) {
            $price = $this->Model->getTNO_data($data['MerchantTradeNo']);
            $point = $point["{$price}"];
            $this->db->trans_begin();
            $this->Model->updatePoint($point);
            $this->Model->updatePaymentHistoryStatus($data['MerchantTradeNo']);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                header("Location: https://tp.edxmpp.org/stored_value?status=2");
                return;
            } else {
                $this->db->trans_commit();
                header("Location: https://tp.edxmpp.org/stored_value?status=1");
                return;
            }
        }
    }
}
