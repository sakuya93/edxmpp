<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClassStudent_information extends Infrastructure
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Taipei');
        $this->load->model("classStudent_information_model", "Model", TRUE);
    }

    public function index($type = "type_live_course")
    {
        $data = new stdClass();
        if (isset($_SESSION['Tid'])) {
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../become_teacher">修改老師資料</a>';
                $data->course_management_link = '<a class="nav-link" href="../course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data->name = $name != '' ? $name : 'XXX';
            $data->photo_path = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';

//            $data->classOption = $this->getClassOption("../");
            $data->RightInformationColumn = $this->getRightInformationColumn('../', $data->photo_path, $data->name);

            $data->headerRightBar = $this->getHeaderRightBar('../', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('../');

            //取得課程選項 start
            $option = $this->Model->getOption();
            $data->option = "";
            foreach ($option as $temp)
                $data->option .= "<option value=\"{$temp->option}\">{$temp->option}</option>";
            //取得課程選項 end

            $data->type = $type;

            $this->load->view('teacher/classStudent_information_view', $data);

            $this->load->view('window/teacher/score_and_comment_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function addComment(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '學生ID不可為空'),
            1 => array('key' => 'not null', 'msg' => '新增資料不完整'),
            2 => array('key' => 'not null', 'msg' => '學生評分不可為空'),
            3 => array('key' => 'not null', 'msg' => '學生評語不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $uuid = uniqid();
        $insert = array(
            't_id' => $_SESSION['Tid'],
            'm_id' => $data['id1'],
            'sc_id' => $data['id2'],
            'l_id' => $data['id3'],
            'ce_id' => $uuid,
            'ce_comment' => $data['comment'],
            'ce_level' => $data['score'],
            'ce_time' => date("Y/m/d H:i:s")
        );
        if($this->Model->checkComment($insert)){
            echo json_encode(array('status' => false, 'msg' => '已對此學生評論'));
            return;
        }
        if ($this->Model->addComment($insert))
            echo json_encode(array('status' => true, 'msg' => '新增學生評價成功', 'id' => $uuid, 'id2' => $data['id1']));
        else
            echo json_encode(array('status' => false, 'msg' => '新增學生評價失敗'));
    }

    public function deleteComment()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        if ($this->Model->deleteComment($data['id1'], $data['id2']))
            echo json_encode(array('status' => true, 'msg' => '刪除學生評價成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '刪除學生評價失敗'));
    }

    public function changeComment(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            1 => array('key' => 'not null', 'msg' => '學生ID不可為空'),
            2 => array('key' => 'not null', 'msg' => '學生評分不可為空'),
            3 => array('key' => 'not null', 'msg' => '學生評語不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $update = array(
            'ce_comment' => $data['comment'],
            'ce_level' => $data['score'],
            'ce_time' => date("Y/m/d H:i:s")
        );
        if($this->Model->changeComment($update, $data['id']))
            echo json_encode(array('status' => true, 'msg' => '修改評價成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '修改評價失敗'));
    }

    public function getLiveComment($courseType = ''){
        echo json_encode($this->Model->getLiveComment($courseType));
    }

    public function getFilmComment(){
        echo json_encode($this->Model->getFilmComment());
    }
}

