<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherCheck extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_teachercheck_model", "Model", TRUE);
    }

    public function index()
    {
        if(!$this->getlogin())
            redirect(base_url('TPManager'));
        $this->load->view('TeacherCheck_view');
    }

    public function getNotCheck(){
        echo json_encode($this->Model->getNotCheck());
    }

    public function getCheck(){
        echo json_encode($this->Model->getCheck());
    }

    public function getBanCheck(){
        echo json_encode($this->Model->getBanCheck());
    }

    public function getDesignatedAdministrator(){
        echo json_encode($this->Model->getDesignatedAdministrator());
    }

    public function checkPass(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->checkBanBecomeTeacher($data['id'])) {
            if ($this->Model->checkPass($data['id']))
                echo json_encode(array('status' => true, 'msg' => '審核通過設定成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '審核通過設定失敗'));
        }
        else
            echo json_encode(array('status' => false, 'msg' => '此帳號被禁止成為老師'));
    }

    public function banBecomeTeacher(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->banBecomeTeacher($data['id']))
            echo json_encode(array('status' => true, 'msg' => '禁止成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '禁止失敗'));
    }

    public function cancelBanBecomeTeacher(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->cancelBanBecomeTeacher($data['id']))
            echo json_encode(array('status' => true, 'msg' => '取消禁止成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '取消禁止失敗'));
    }

    public function cancelTeacherIdentity(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->cancelTeacherIdentity($data['id']))
            echo json_encode(array('status' => true, 'msg' => '取消禁止成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '取消禁止失敗'));
    }

    public function logoutTeacherIdentity(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->cancelBanBecomeTeacher($data['id']))
            echo json_encode(array('status' => true, 'msg' => '註銷老師身分成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '註銷老師身分失敗'));
    }

    public function setDesignatedAdministrator(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->setDesignatedAdministrator($data['id']))
            echo json_encode(array('status' => true, 'msg' => '指定前端管理員成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '指定前端管理員失敗'));
    }
    
}
