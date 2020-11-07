<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FundraisingCourse_management extends label
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("fundraising_course_management_model", "Model", TRUE);
    }

    public function index($id = '') //募資課程編輯頁面
    {
        if (isset($_SESSION['Tid'])) {
            if($this->Model->checkFundraisingCourseIsNull($id) != 1){
                redirect(base_url("course_management/index/type_fundraising_course"));
            }
//            if($this->Model->getFundraisingCourseStatus($id)->fc_status != 1){
//                redirect(base_url("course_management/index/type_fundraising_course"));
//            }
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                $data['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="../course_management/index/type_live_course">課程管理</a>';
            }

            $data['Information'] = $this->Model->getFundraisingCourseData($id);

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data['name'] = $name != '' ? $name : 'XXX';
            $data['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $data['classOption'] = $this->getClassOption("../");
            $data['RightInformationColumn'] = $this->getRightInformationColumn('../', $data['photo_path'], $data['name']);

            $data['headerRightBar'] = $this->getHeaderRightBar('../', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
            $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../');

            $this->load->view('teacher/edit_fundraisingCourse_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');

        } else
            redirect(base_url('home'));
    }

    public function addFundraisingCourse_view()
    { //新增募資課程呈現頁面
        if (isset($_SESSION['Tid'])) {
            $this->checkOneLogin();

            if (!$this->check_memberData()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;

            $data['name'] = $name != '' ? $name : 'XXX';
            $data['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $data['classOption'] = $this->getClassOption("");
            $data['RightInformationColumn'] = $this->getRightInformationColumn('', $data['photo_path'], $data['name']);

            $data['headerRightBar'] = $this->getHeaderRightBar('', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
            $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');
            $data['FundraisingCourseData'] = '';

            $this->load->view('teacher/fundraising_course_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');

        } else
            redirect(base_url('student'));
    }

    public function addFundraisingCourse()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            0 => array('key' => 'not null', 'msg' => '請填寫課程名稱'),
            1 => array('key' => 'not null', 'msg' => '請填寫影片網址'),
            2 => array('key' => 'not null', 'msg' => '請選擇募資課程的上課類型'),
            3 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            5 => array('key' => 'not null', 'msg' => '請選擇貨幣種類'),
            6 => array('key' => '^\d+$', 'msg' => '課程時數只能填寫數字'),
            7 => array('key' => '^\d+$', 'msg' => '原始價格只能填寫數字'),
            8 => array('key' => '^\d+$', 'msg' => '募資價格只能填寫數字'),
            9 => array('key' => '^\d+$', 'msg' => '預計人數只能填寫數字'),
            10 => array('key' => 'not null', 'msg' => '請填寫結束時間'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $this->load->library('MY_currency');
        if ($this->my_currency->checkCurrency($data['currency'])) {
            echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
            return;
        }

        if (!$this->Model->check_FundraisingCourseRepeat($data['course_name'], $data['type'])) {
            echo json_encode(array('status' => false, 'msg' => '募資課程名稱重複，或者募資課程與普通課程重複，請更改課程名稱'));
            return;
        }
        $uuid = uniqid();
        $config['upload_path'] = "resource/image/teacher/fundraisingCourse/";
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = $uuid;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            echo json_encode(array('status' => false, 'msg' => "新增圖片失敗"));
            return false;
        } else {
            $image = $this->upload->data();
        }
        $label = "";
        $labelArray = explode(",", $data['label']);
        foreach ($labelArray as $temp) {
            if (strpos(mb_convert_encoding($label, 'utf-8'), mb_convert_encoding($temp, 'utf-8')) === false)
                $label .= "{$temp}、";
        }

        $insert = array(
            'fc_id' => $uuid,
            't_id' => $_SESSION['Tid'],
            'fc_courseName' => $data['course_name'],
            'fc_filmUrl' => $data['film_rul'],
            'fc_hours' => $data['hours'],
            'fc_type' => $data['type'],
            'fc_courseIntroduction' => $data['courseIntroduction'],
            'fc_briefIntroduction' => $data['brief_introduction'],
            'fc_currency' => $data['currency'],
            'fc_normalPrice' => $data['normal_price'],
            'fc_fundraisingPrice' => $data['fundraising_price'],
            'fc_expectedNumber' => $data['expected_number'],
            'fc_remainingNumber' => $data['expected_number'],
            'fc_endTime' => $data['endTime'],
            'fc_label' => $label,
            'fc_image' => $image['orig_name'],
            'fc_status' => '1'
        );

        if ($this->Model->addFundraisingCourse($insert)) {
            echo json_encode(array('status' => true, 'msg' => '新增募資課程成功', 'url' => $uuid));
        } else
            echo json_encode(array('status' => false, 'msg' => '新增募資課程失敗'));
    }

    public function editFundraisingCourse()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => '', 'msg' => ''),
            1 => array('key' => 'not null', 'msg' => '請填寫課程名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫影片網址'),
            3 => array('key' => 'not null', 'msg' => '請選擇募資課程的上課類型'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            5 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            6 => array('key' => 'not null', 'msg' => '請選擇貨幣種類'),
            7 => array('key' => '^\d+$', 'msg' => '課程時數只能填寫數字'),
            8 => array('key' => '^\d+$', 'msg' => '原始價格只能填寫數字'),
            9 => array('key' => '^\d+$', 'msg' => '募資價格只能填寫數字'),
            10 => array('key' => '^\d+$', 'msg' => '預計人數只能填寫數字'),
            11 => array('key' => 'not null', 'msg' => '請填寫結束時間'),
        );

        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $this->load->library('MY_currency');
        if ($this->my_currency->checkCurrency($data['currency'])) {
            echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
            return;
        }

        $update = array(
            'fc_courseName' => $data['course_name'],
            'fc_filmUrl' => $data['film_rul'],
            'fc_type' => $data['type'],
            'fc_courseIntroduction' => $data['courseIntroduction'],
            'fc_briefIntroduction' => $data['brief_introduction'],
            'fc_currency' => $data['currency'],
            'fc_hours' => $data['hours'],
            'fc_normalPrice' => $data['normal_price'],
            'fc_fundraisingPrice' => $data['fundraising_price'],
            'fc_expectedNumber' => $data['expected_number'],
            'fc_remainingNumber' => $data['expected_number'],
            'fc_endTime' => $data['endTime'],
        );

        if ($this->Model->editFundraisingCourse($update, $data['id']))
            echo json_encode(array('status' => true, 'msg' => '更新募資課程資料成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '更新募資課程資料失敗'));
    }

    public function update_image()
    {
        $this->checkOneLogin();
        $data = $this->input->post();

        $imagePath = "resource/image/teacher/fundraisingCourse/";
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);

        //更新圖片
        if ($this->upload->do_upload('thumbnail')) {
            $image = $this->upload->data();
            $ordImage = $this->Model->getOrdImage($data['id']);
            unlink("{$imagePath}{$ordImage->name}");

            rename("{$imagePath}{$image['orig_name']}", "{$imagePath}{$ordImage->name}");
            echo json_encode(array('status' => true, 'msg' => '更新縮圖成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '更新縮圖失敗!!'));
        }
    }

    public function fundraisingFailureNotice(){

    }
}
