<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_favorite extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("course_favorite_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();
            $notice = $this->getNotice();
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
            $this->load->view('student/course_favorite_view', $data);
//            $this->load->view('student/dashboard_view', $data);
//            $this->load->view('window/student/collection_course_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function getCourseFavorite(){
        $data = $this->input->post();
        //1:直播、2:影片、3:募資

        if($data['status'] == 1) {
            $returnData = $this->Model->getLiveCourseFavorite();
        }elseif($data['status'] == 2)
            $returnData = $this->Model->getFilmCourseFavorite();
        elseif($data['status'] == 3)
            $returnData = $this->Model->getFundraisingCourseFavorite();
        else
            return;

        echo json_encode($returnData);
    }

    public function favorite()
    {
        $this->checkOneLogin();
        $Data = $this->input->post();

        $this->load->library('MY_favorite');
        echo $this->my_favorite->addFavorite($Data);
    }

    public function cancel_favorite()
    {
        $Data = $this->input->post();

        $this->load->library('MY_favorite');
        echo $this->my_favorite->deleteFavorite($Data);
    }
}

