<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class My_learn_process extends Infrastructure
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("my_learn_process_model", "Model", TRUE);
    }

    public function index($type = "type_live_course")
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data->course_management_link = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data->course_management_link = '<a class="nav-link" href="../modify_member_information">帳號設定</a>';
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

            $data->type = $type;
            $this->load->view('student/my_learn_process_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
            $this->load->view('window/student/comment_window');
        } else
            redirect(base_url('home'));
    }

    public function addComment()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '課程ID不可為空'),
            1 => array('key' => 'not null', 'msg' => '老師評語不可為空'),
            2 => array('key' => '^\d+$', 'msg' => '老師評分只能填寫數字或不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $uuid = uniqid();
        $insert = array(
            'm_id' => $_SESSION['Mid'],
            'l_id' => $data['id1'],
            'sc_id' => $data['id2'],
            'ce_id' => $uuid,
            'ce_comment' => $data['comment'],
            'ce_level' => $data['score'],
            'ce_time' => date("Y/m/d H:i:s")
        );
        $this->db->trans_begin();
        if($this->Model->checkComment($insert)){
            echo json_encode(array('status' => false, 'msg' => '已對此課程評論'));
            return;
        }

        $this->Model->addComment($insert);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array('status' => false, 'msg' => '新增課程評價失敗'));
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array('status' => true, 'msg' => '新增課程評價成功', 'id' => $uuid, 'id2' => $data['id1']));
		}
    }

    public function addCommentFilm()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '課程ID不可為空'),
            1 => array('key' => 'not null', 'msg' => '老師評語不可為空'),
            2 => array('key' => '^\d+$', 'msg' => '老師評分只能填寫數字或不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $uuid = uniqid();
        $insert = array(
            'm_id' => $_SESSION['Mid'],
            'cf_id' => $data['id1'],
            'sc_id' => $data['id2'],
            'ce_id' => $uuid,
            'ce_comment' => $data['comment'],
            'ce_level' => $data['score'],
            'ce_time' => date("Y/m/d H:i:s")
        );
        if($this->Model->checkComment($insert)){
            echo json_encode(array('status' => false, 'msg' => '已對此課程評論'));
            return;
        }
        $this->db->trans_begin();
        $this->Model->addCommentFilm($insert);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '新增課程評價失敗'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '新增課程評價成功', 'id' => $uuid, 'id2' => $data['id1']));
        }
    }

    public function deleteComment()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '刪除資料不完整'),
            1 => array('key' => 'not null', 'msg' => '直播ID不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->Model->deleteComment($data['id1'], $data['id2']))
            echo json_encode(array('status' => true, 'msg' => '刪除課程評價成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '刪除課程評價失敗'));
    }

    public function getComment(){
        $data = $this->input->get();
        $data['s'] = substr($data['s'], -16);
        echo json_encode($this->Model->getComment($data['s']));
    }

    public function changeComment(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '課程ID不可為空'),
            1 => array('key' => 'not null', 'msg' => '老師評語不可為空'),
            2 => array('key' => '^\d+$', 'msg' => '老師評分只能填寫數字或不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $update = array(
            'ce_comment' => $data['comment'],
            'ce_level' => $data['score'],
            'ce_time' => date()
        );
        if($this->Model->changeComment($update, $data['id']))
            echo json_encode(array('status' => true, 'msg' => '修改評語成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '修改評語失敗'));
    }
}
