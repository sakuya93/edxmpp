<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_label extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_course_label_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()){
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/course_label_view', $HTML);
            $this->load->view('window/admin/course_label_window');
            $this->load->view('window/admin/hint_window');
        }
        else
            redirect(base_url('TPManager'));
    }

    public function addLabel(){
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if(!$Form_normalization->type){
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if($this->Model->checkLabel($data['label']) > 0 ){
            echo json_encode(array('status' => false, 'msg' => '已有相同的標籤存在'));
            return;
        }

        if($this->Model->addLabel($data['label']))
            echo json_encode(array('status' => true, 'msg' => '新增標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增標籤失敗'));
    }

    public function getLabel(){
        echo json_encode($this->Model->getLabel());
    }

    public function deleteLabel(){
        $data = $this->input->post();
        if($this->Model->deleteLabel($data))
            echo json_encode(array('status' => true, 'msg' => "刪除選項成功"));
        else
            echo json_encode(array('status' => false, 'msg' => "刪除選項失敗"));
    }

}
