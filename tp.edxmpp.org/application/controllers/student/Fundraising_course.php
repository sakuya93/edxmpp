<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fundraising_course extends leaveMessage
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("fundraisingCourse_model", "Model", TRUE);
    }

    public function index($id = '')
    {
        if (!$this->Model->checkRelease($id) /*| $this->Model->checkEndTime($id)*/)
            redirect(base_url('student'));
        $bo = $this->getlogin();
        $search = $this->input->get();
        if($search['c'] != 'TWD' & $search['c'] != 'VND' & $search['c'] != 'MYR')
            redirect(base_url('student'));
        if ($bo) {
            if (!$this->check_memberData()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data['course_management_link'] = '<a class="nav-link" href="../modify_member_information">帳號設定</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="../course_management/index/type_live_course">課程管理</a>';
            }
        } else {
            $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            $data['course_management_link'] = '<a class="nav-link" onclick="undone_teacherData()">課程管理</a>';
        }

        if ($bo) {
            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            if (isset($_SESSION['front_end_admin'])) {
                $data['messagePhoto'] = "resource/image/share/admin.jpg";
                $data['messageName'] = "管理員";
                $data['identity'] = "";
            } else {
                $messageData = $this->Model->getMessageData($this->Model->checkMessageIsMy($id));
                $data['messagePhoto'] = "resource/image/student/photo/{$messageData->m_photo}";
                $data['messageName'] = $messageData->name;
                $data['identity'] = $messageData->identity;
            }
        } else {
            $data['messagePhoto'] = "";
            $data['messageName'] = "";
            $data['identity'] = "";
            $name = '';
            $photo = '';
        }

        $data['name'] = $name != '' ? $name : 'XXX';
        $data['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
        $data['RightInformationColumn'] = $this->getRightInformationColumn('../', $data['photo_path'], $data['name']);
        $data['headerRightBar'] = $this->getHeaderRightBar('../', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
        $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../');
        $data['fundraisingCourse'] = $this->Model->getFundraisingCourseData($id, $search['c']);
        $data['fundraisingCourse']['point'] = $this->Model->getFundraisingCourseDataPoint($id);
        $data['fundraisingCourse']['c'] = $search['c'];

        $data['id'] = $id;
        if ($bo)
            if (isset($_SESSION['Tid']))
                if ($data['fundraisingCourse'][0]->id1 == $_SESSION['Tid'])
                    $data['checkInterest'] = 2;
                else
                    $data['checkInterest'] = $this->Model->checkInterest($id);
            else
                $data['checkInterest'] = $this->Model->checkInterest($id);
        else
            $data['checkInterest'] = 3;

        /*聯繫老師*/
        if ($bo & $data['fundraisingCourse'][0]->id1 != @$_SESSION['Tid']) {
            $contactWindow = $this->Model->getContactWindow($data['fundraisingCourse'][0]->id1);

            if (!isset($contactWindow))
                $contactWindow = "contact_detail('-1', '老師', '', '{$data['fundraisingCourse'][0]->teacherName}', '{$data['fundraisingCourse'][0]->id1}', '')";
            else
                $contactWindow = "contact_detail('-1', '老師', '{$contactWindow->id}', '{$data['fundraisingCourse'][0]->teacherName}', '{$contactWindow->id2}', '{$contactWindow->id3}')";
            $data['contact_window'] = $contactWindow;
        } else {
            $data['contact_window'] = "";
        }

        $data['favorite'] = $this->Model->getCourseFavorite($id);

        $this->load->view('student/fundraising_course_view', $data);
        $this->load->view('window/share/notice_window');
        $this->load->view('window/home/registered_window');
        $this->load->view('window/home/signIn_window');
        $this->load->view('window/hint_window');
    }

    public function get_mp4_video_url()
    {
        $url = $this->input->post();
//        $url["film_url"] = "6Nydc0vHg40"; //測試用

        parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=" . $url["film_url"]), $info);
        $streams = json_decode($info['player_response']);
        $streams = $streams->streamingData->formats[1]->url;
//        $streams = $streams->streamingData->adaptiveFormats[0]->url;

        echo json_encode(urlencode($streams), JSON_PRETTY_PRINT); //傳回去時編碼不然網址會被複製
    }

    function get_error($ErrorExaction)
    {
        $myObj = new stdClass();
        $myObj->error = true;
        $myObj->msg = $ErrorExaction;
        $myObj->madeBy = "A.El-zahaby";
        $myObj->instagram = "egy.js";
        $myJSON = json_encode($myObj, JSON_PRETTY_PRINT);
        echo $myJSON;
        exit;
    }

    function get_log($dump)
    {
        if (isset($_GET['log'])) var_dump($dump) . '\n\n\n';
    }

    public function addVideoWatchHistory()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '資料不完整')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $VWH = $this->Model->getVideoWatchHistory($data['id'], $data['index']);

        if ($VWH == null) {
            $bo = true;
            $insert = array(
                'cf_id' => $data['id'],
                'm_id' => $_SESSION['Mid'],
                'vwh_id' => uniqid(),
                'cf_actualMovieIndex' => $data['index'],
                'vwh_date' => 5,
                'vwh_endDate' => date('Y-m-d H:i:s')
            );
        } else {
            $bo = false;
            $insert = array(
                'cf_id' => $data['id'],
                'm_id' => $_SESSION['Mid'],
                'vwh_id' => uniqid(),
                'cf_actualMovieIndex' => $data['index'],
                'vwh_date' => $VWH->date += 5,
                'vwh_endDate' => date('Y-m-d H:i:s')
            );
        }

        if ($this->Model->addVideoWatchHistory($insert, $bo))
            echo json_encode(array('status' => true));
        else
            echo json_encode(array('status' => false));

    }

    public function fundraisingCourseInterested()
    {
        $data = $this->input->post();

        if ($this->Model->checkInterest($data['id']) != 0) {
            echo json_encode(array('status' => false, 'msg' => '已經對此募資課程表示有興趣'));
            return;
        }
        $this->db->trans_start();
        $point = str_replace(",", "", $this->Model->getFundraisingCourseDataPoint($data['id']));
        if ($this->Model->lessPoint($data['id'], $point)) {
            echo json_encode(array('status' => false, 'msg' => '鑽石餘額不足'));
            return;
        }
        $insert = array(
            'fc_id' => $data['id'],
            'm_id' => $_SESSION['Mid'],
        );

        $this->Model->fundraisingCourseInterested($insert);
        $this->Model->reduceFundraisingCoursePeople($data['id']);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '操作失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '感謝您!'));
        }
    }

    public function getMessage()
    {
        $data = $this->input->post();
//        if($this->Model->checkFundraising($data['id']) != 1){
//            echo json_encode(array('不明錯誤，請刷新頁面後重新嘗試'));
//            return;
//        }

        echo json_encode($this->Model->getMessage($data['id'], $data['index']));
    }

    public function getMessageReply()
    {
        $data = $this->input->post();
//        if($this->Model->checkFundraising($data['id2']) != 1){
//            echo json_encode(array('不明錯誤，請刷新頁面後重新嘗試'));
//            return;
//        }

        echo json_encode($this->Model->getMessageReply($data['id'], $data['index']));
    }

    public function ClassReport()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => '', 'msg' => ''),
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            3 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            4 => array('key' => 'not null', 'msg' => '檢舉選項不可為空'),
            5 => array('key' => '', 'msg' => ''),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            return json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $this->load->library('my_report');
        echo $this->my_report->addClassReport($data);
    }
}
