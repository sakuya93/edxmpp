<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_tasks extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("daily_tasks_model", "Model", TRUE);
    }

    public function index()
    {
//        if (!isset($_SESSION['Tid']))
//            redirect(base_url('student'));
        $data = new stdClass();
        if ($this->getlogin()) {
////////////////////////////////          必要資料確認及匯入資訊 Start          ///////////////////////////////
            $this->checkOneLogin();
            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                redirect(base_url('student'));
                $data->course_management_link = '';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data->course_management_link = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data->name = $name != '' ? $name : 'XXX';
            $data->photo_path = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';

//            $data->classOption = $this->getClassOption('');
            $data->RightInformationColumn = $this->getRightInformationColumn('', $data->photo_path, $data->name);

            $data->headerRightBar = $this->getHeaderRightBar('', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('');
            $receive = $this->Model->getReceiveData();
            $day = date('d');
            if($receive->task1 != $day)
                $data->task1 = false;
            else
                $data->task1 = true;
            if($receive->task2 == null)
                $data->task2 = false;
            else
                $data->task2 = true;
            if($receive->task3 == null)
                $data->task3 = false;
            else
                $data->task3 = true;
////////////////////////////////          必要資料確認及匯入資訊 End          ///////////////////////////////

            $this->load->view('student/daily_tasks_view', $data);
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function addDailyCheckIn()
    {
        if ($this->Model->checkEmailCertification() != 1) {
            echo json_encode(array('status' => false, 'msg' => '請先完成信箱認證'));
            return;
        }
        if($this->Model->checkReceive("task1") > 0){
            echo json_encode(array('status' => false, 'msg' => '已經領取過囉!'));
            return;
        }
        $this->db->trans_begin();
        $gold = $this->Model->getGold();


        if ($gold != null & $gold->gold >= 900) {
            $this->Model->lessGold();
            $this->Model->addDiamond();
        }else
            $this->Model->addGold(100, $gold->gold);

        $this->Model->addDailyCheckIn("task1");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '領取失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '領取成功'));
        }
    }

    public function addFB_share()
    {
        if ($this->Model->checkEmailCertification() != 1) {
            echo json_encode(array('status' => false, 'msg' => '請先完成信箱認證'));
            return;
        }
        if($this->Model->checkReceive("task2") > 0){
            echo json_encode(array('status' => false, 'msg' => '已經領取過囉!'));
            return;
        }
        $this->db->trans_begin();
        $gold = $this->Model->getGold();
        if ($gold != null & $gold->gold >= 800) {
            $this->Model->lessGold();
            $this->Model->addDiamond();
        }else
            $this->Model->addGold(200, $gold->gold);
        $this->Model->addDailyCheckIn("task2");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '領取失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '領取成功'));
        }
    }

    public function addLINE_share()
    {
        if ($this->Model->checkEmailCertification() != 1) {
            echo json_encode(array('status' => false, 'msg' => '請先完成信箱認證'));
            return;
        }
        if($this->Model->checkReceive("task3") > 0){
            echo json_encode(array('status' => false, 'msg' => '已經領取過囉!'));
            return;
        }
        $this->db->trans_begin();
        $gold = $this->Model->getGold();

        if ($gold != null & $gold->gold >= 800) {
            $this->Model->lessGold();
            $this->Model->addDiamond();
        }else
            $this->Model->addGold(200, $gold->gold);
        $this->Model->addDailyCheckIn("task3");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '領取失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '領取成功'));
        }
    }
}