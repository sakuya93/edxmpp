<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buy_record extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("buy_record_model", "Model", TRUE);
    }

    public function index()
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();

            //初始化變數
            $HTML = array(
                'content' => '',
                'name' => '',
                'photo_path'
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
            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $HTML['classOption'] = $this->getClassOption();
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('', $HTML['photo_path'], $HTML['name']);
            $HTML['headerRightBar'] = $this->getHeaderRightBar('', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');


            $this->load->view('student/buy_record_view', $HTML);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function getPaymentHistoryPoint()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getPaymentHistoryPoint());
    }

    public function getPaymentHistoryClass()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getPaymentHistoryClass());
    }

    public function getClassData()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getClassData($data['id']));
    }


    public function getOrderClassData()
    {
        $data = $this->input->post();
        $class = $this->Model->getClass($data['order']);
        $classArray = mb_split("、", $class);
        echo json_encode($this->Model->getOrderClassData($classArray));
    }
}

