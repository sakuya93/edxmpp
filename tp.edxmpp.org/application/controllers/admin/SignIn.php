<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignIn extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_signin_model", "Model", TRUE);
    }

    public function index()
    {
        $this->load->view('admin/signIn_view');
        $this->load->view('window/hint_window');
    }

    public function signIn(){
        $data = $this->Form_security_processing($this->input->post());
        if($this->Model->signIn($data)) {
            if($data['account'] == 'test1') {
                if($this->db->select('*')->from('admin')->where('account', 'test1')->where('status', '1')->get()->num_rows() == 1)
                    $this->db->where('account', 'test1')->update('admin', array('status' => '0'));
                else
                    $this->db->where('account', 'test1')->update('admin', array('status' => '1'));
                echo json_encode(array('status' => true, 'url' => 'TPManager',  'msg' => '登入成功!'));
                return;
            }
            $_SESSION['admin_name'] = $data['account'];
            echo json_encode(array('status' => true, 'url' => 'admin_management',  'msg' => '登入成功!'));
        }else
            echo json_encode(array('status' => false, 'msg' => '帳號密碼錯誤，請重新嘗試'));
    }
}
