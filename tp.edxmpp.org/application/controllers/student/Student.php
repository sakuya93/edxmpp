<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("student_model", "Model", TRUE);
    }

    /*public function _remap($url = '',$type1 = '', $type2 = '')
    {
        $language = $type1;
        if($language == null) {
            $language = strtolower(strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ','));
        }
        if($language == 'zh')$language = 'zh-tw';
        $this->lang->load('student', $language);
        $GLOBALS['viewLang'] = $this->lang->line('view');
        $GLOBALS['controllerLang'] = $this->lang->line('controller');
        if($url == '' | $url == 'index')
            $this->index();
        elseif($url == 'registered')
            $this->registered();
        elseif($url == 'login')
            $this->login();
    }*/

    public function index()
    {
        if ($this->getlogin()) {
//            if ($this->Model->completePersonalInformation() != 1)
//                redirect(base_url('modify_member_information'));
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

            $this->load->view('student/student_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }
}
