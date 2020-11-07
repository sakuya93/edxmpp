<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_options extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_course_options_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()){
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/course_options_view', $HTML);
            $this->load->view('window/admin/course_options_window');
            $this->load->view('window/admin/hint_window');
        }
        else
            redirect(base_url('TPManager'));
    }

    public function load_table_data()
    {
        echo json_encode($this->Model->getdata());
    }

    public function addOption()
    {
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '請填寫選項標題'),
            1 => array('key' => 'not null', 'msg' => '請填寫選項'),
            2 => array('key' => 'not null', 'msg' => '請填寫關鍵詞'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);

        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $insert = array(
            'id' => uniqid(),
            'title' => $data['title'],
            'option' => $data['option'],
            'key_words' => $data['key_words'],
        );
        if ($this->Model->addOption($insert))
            echo json_encode(array('status' => true, 'msg' => "新增選項成功"));
        else
            echo json_encode(array('status' => false, 'msg' => "新增失敗"));
    }

    public function deleteOption(){
        $data = $this->input->post();
        if($this->Model->deleteOption($data))
            echo json_encode(array('status' => true, 'msg' => "刪除選項成功"));
        else
            echo json_encode(array('status' => false, 'msg' => "刪除選項失敗"));
    }

}
