<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_sales extends leaveMessage
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("teacher_sales_model", "Model", TRUE);
    }

    public function index($kind = "", $course_id = "")
    {
        if($this->Model->checkLive($course_id) != 1){
            redirect(base_url('student'));
        }
        $bo = $this->getlogin();
        $result = array();
        $result['Events'] = array();

        //初始化變數

        //初始化變數結束
        if ($bo) {
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $result['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $result['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $result['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $result['course_management_link'] = '<a class="nav-link" href="../../modify_member_information">帳號設定</a>';
            } else {
                $result['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $result['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
            }

            $result['isLogin'] = $bo;
        } else {
            $result['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            $result['course_management_link'] = '<a class="nav-link" onclick="undone_teacherData()">課程管理</a>';
        }

        $searchData = $this->input->get();
        $dataArray = $this->Model->getCourses_data($kind, $course_id);
        $result['id'] = $dataArray[0]->id; //liveID
        $result['id2'] = $dataArray[0]->id2; //老師ID
        $result['name'] = $dataArray[0]->name; //老師名字
        $result['photo'] = $dataArray[0]->photo; //老師照片
        $result['type'] = $dataArray[0]->type; //課程種類
        $result['actualMovie'] = $dataArray[0]->actualMovie; //課程名稱
        $result['speakLanguage'] = $dataArray[0]->speakLanguage; //會說語言
        $result['experienceFilm'] = $dataArray[0]->experienceFilm; //體驗影片
        $result['des'] = $dataArray[0]->des; //老師自介
        $result['price'] = $dataArray[0]->price; //課程價錢
        $result['hours'] = $dataArray[0]->hours; //課程時數
        $result['introduction'] = $dataArray[0]->introduction; //課程介紹
        $result['brief_introduction'] = $dataArray[0]->brief_introduction; //課程簡介

        $result['NumberLessonsPreferential'] = $this->Model->getNumberLessonsPreferential($course_id, @$searchData['c']);

        $result['preferential_content'] = "";
        $result['more_offers_content'] = "";
        if ($bo & $dataArray[0]->id2 != @$_SESSION['Tid']) {
            $contactWindow = $this->Model->getContactWindow($dataArray[0]->id2);
            if (!isset($contactWindow))
                $contactWindow = "contact_detail('-1', '老師', '', '{$dataArray[0]->name}', '{$dataArray[0]->id2}', '')";
            else
                $contactWindow = "contact_detail('-1', '老師', '{$contactWindow->id}', '{$dataArray[0]->name}', '{$contactWindow->id2}', '{$contactWindow->id3}')";
            $result['contact_window'] = $contactWindow;
        } else {
            $result['contact_window'] = "";
        }
        //處理匹配時間
        $data_matchTime = $this->Model->getMatchTime($dataArray[0]->id);
        if ($data_matchTime) {
            foreach ($data_matchTime as $index => $tempData) {
                $result['Events'][$index]['id'] = $tempData->lt_id;
                $result['Events'][$index]['title'] = substr($tempData->lt_time, 11) . "\n 最多: {$tempData->lt_maxPeople}人\n備註:{$tempData->lt_note}";
                $result['Events'][$index]['start'] = substr($tempData->lt_time, 0, 10);
            }
        }

        if ($dataArray[0]->teacherStatus != 1) {
            $result['preferential_content'] .= "<h4 style='font-size:24px;color: grey;margin-top: 10px'>此課程的上課老師已被封鎖，無法購買此課程!</h4>";
        } else if (count($result['NumberLessonsPreferential']) > 3) { //如果優惠超過3個就顯示出more按鈕
            $result['more_offers_content'] = "
                    <div onclick=\"more_display_change()\">
                        <span class=\"more_offers_text col-sm-12\">顯示更多優惠</span>
                        <i class=\"fa fa-chevron-down arrow col-sm-12\"></i>
                    </div>
                ";
        } else if (count($result['NumberLessonsPreferential']) == 0) {
            $result['preferential_content'] .= "<h4 style='font-size:24px;color: grey;margin-top: 10px'>目前無優惠!</h4>";
        }

        for ($i = 0; $i < count($result['NumberLessonsPreferential']); $i++) {
            if ($i >= 3) { //第四筆優惠開始都會觸發more事件
                $result['preferential_content'] .= '<div class="selling_card more_item row"';
            } else {
                $result['preferential_content'] .= '<div class="selling_card row"';
            }
            if ($bo)
                $url = "../../shopping_cart/addShopping/live/{$result['id']}/{$result['NumberLessonsPreferential'][$i] -> number}";
            else
                $url = "";
            $price = sprintf("%.2f", $result['NumberLessonsPreferential'][$i]->discountedPrice);
            if($result['NumberLessonsPreferential'][$i] -> number == -1){
                if($this->Model->checkFixedClassIfBuy($course_id) == 1){
                    $result['preferential_content'] .= "
                        onclick=\"\">
                        <div class=\"price_hours col-sm-10\">
                            <h5>您已購買了!</h5>
                            <span>固定上課時間課程</span>
                        </div>
                        <div class=\"right_arrow col-sm-1\">
                            <i class=\"fa fa-angle-right\"></i>
                        </div>
                    </div>";
                }else{
                    $result['preferential_content'] .= "
                  onclick=\"window.location='{$url}'\">
                        <div class=\"price_hours col-sm-10\">
                            <h5>" . @$searchData['c'] . "$ {$price}</h5>
                            <span>固定上課時間課程</span>
                        </div>
                        <div class=\"right_arrow col-sm-1\">
                            <i class=\"fa fa-angle-right\"></i>
                        </div>
                    </div>";
                }

            }else {
                $result['preferential_content'] .= "
                  onclick=\"window.location='{$url}'\">
                        <div class=\"price_hours col-sm-10\">
                            <h5>" . @$searchData['c'] . "$ {$price}</h5>
                            <span>{$result['NumberLessonsPreferential'][$i] -> number}堂{$result['hours']}小時/課</span>
                        </div>
                        <div class=\"right_arrow col-sm-1\">
                            <i class=\"fa fa-angle-right\"></i>
                        </div>
                    </div>
                ";
            }
        }
        if ($bo) {
            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            if(isset($_SESSION['front_end_admin'])) {
                $result['messagePhoto'] = "resource/image/share/admin.jpg";
                $result['messageName'] = "管理員";
                $result['identity'] = "";
            }else{
                $messageData = $this->Model->getMessageData($this->Model->checkMessageIsMy($course_id));
                $result['messagePhoto'] = "resource/image/student/photo/{$messageData->m_photo}";
                $result['messageName'] = $messageData->name;
                $result['identity'] = $messageData->identity;
            }

        } else {
            $result['messagePhoto'] = "";
            $result['messageName'] = "";
            $result['identity'] = "";
            $name = '';
            $photo = '';
        }

        $result['m_name'] = $name != '' ? $name : 'XXX';
        $result['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//        $result['classOption'] = $this->getClassOption("../../");
        $result['RightInformationColumn'] = $this->getRightInformationColumn('../../', $result['photo_path'], $result['m_name']);

        $result['headerRightBar'] = $this->getHeaderRightBar('../../', $result['photo_path'], $result['become_teacher_link'], $result['course_management_link']);
        $result['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');
        $result['favorite'] = $this->Model->getCourseFavorite($course_id);

        $this->load->view('student/teacher_sales_view', $result);
//        $this->load->view('window/student/collection_teacher_window');
        $this->load->view('window/home/registered_window');
        $this->load->view('window/home/signIn_window');
        $this->load->view('window/share/notice_window');
        $this->load->view('window/hint_window');
        $this->load->view('window/share/report_user_window');
    }

    public function getMessage(){
        $data = $this->input->post();
        if($this->Model->checkLive($data['id']) != 1){
            echo json_encode(array('不明錯誤，請刷新頁面後重新嘗試'));
            return;
        }

        echo json_encode($this->Model->getMessage($data['id'], $data['index']));
    }

    public function getMessageReply(){
        $data = $this->input->post();
        if($this->Model->checkLive($data['id2']) != 1){
            echo json_encode(array('不明錯誤，請刷新頁面後重新嘗試'));
            return;
        }

        echo json_encode($this->Model->getMessageReply($data['id'], $data['index']));
    }

    public function ClassReport(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            3 => array('key' => 'not null', 'msg' => '檢舉選項不可為空'),
            4 => array('key' => '', 'msg' => ''),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            return json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $this->load->library('my_report');
        echo $this->my_report->addClassReport($data);
    }

    public function addExperience_class(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            3 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            return json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $t_id = $this->Model->getCourseTid($data['id']);
        if($t_id == null){
            echo json_encode(array('status' => false, 'msg' => '找不到老師資料，請聯繫管理員'));
            return;
        }
    }

    public function addExperienceClass(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '資料不完整'),
            2 => array('key' => '', 'msg' => ''),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if(!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }


        if($this->Model->checkDuplicateApplication($data['courseID']) == 1){
            echo json_encode(array('status' => false, 'msg' => '已經申請過囉，請耐心等待老師回應。'));
            return;
        }

        $insert = array(
          'l_id' => $data['courseID'],
          'm_id' => $_SESSION['Mid'],
          't_id' => $data['teacherID'],
          'ec_date' => date('Y-m-d H:i:s'),
        );

        if($this->Model->addExperienceClass($insert))
            echo json_encode(array('status' => true, 'msg' => '申請成功，請耐心等待老師回復。'));
        else
            echo json_encode(array('status' => false, 'msg' => '申請失敗，請重新嘗試'));
    }
}
