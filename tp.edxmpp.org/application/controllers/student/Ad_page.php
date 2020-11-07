<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ad_page extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Course_favorite_model", "Model", TRUE);
    }

    public function index()
    {
        $bo = $this->getlogin();
        if ($bo)
            $this->checkOneLogin();
        //初始化變數結束
        if ($bo) {
            if (!$this->check_memberData()) {
                $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $HTML['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="../../modify_member_information">帳號設定</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
            }
        } else {
            $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            $HTML['course_management_link'] = '<a class="nav-link" onclick="undone_teacherData()">課程管理</a>';
        }

        $nav = new stdClass();
        if ($bo)
            $nav = $this->get_nav();
        else {
            $nav->name = '';
            $nav->photo = '';
        }
        $HTML['name'] = $nav->name != '' ? $nav->name : 'XXX';
        $HTML['photo_path'] = $nav->photo != '' ? $nav->photo . "?v=" . uniqid() : 'noPhoto.jpg';
//        $HTML['classOption'] = $this->getClassOption("");
        $HTML['RightInformationColumn'] = $this->getRightInformationColumn('', $HTML['photo_path'], $HTML['name']);
        $HTML['headerRightBar'] = $this->getHeaderRightBar('', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
        $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');

        $this->load->view('student/ad_page_view', $HTML);
        $this->load->view('window/share/notice_window');
        $this->load->view('window/home/registered_window');
        $this->load->view('window/home/signIn_window');
        $this->load->view('window/hint_window');

    }

}

