<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_management extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_member_management_model", "Model", TRUE);
    }

    public function index()
    {
        if($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/member_management_view', $HTML);
        }
        else
            redirect(base_url('TPManager'));
    }

    public function getMemberData($type = 0){
        echo json_encode($this->Model->getMemberData($type));
    }

    public function blockadeMember(){
        $data = $this->Form_security_processing($this->input->post());
        $this->db->trans_start();
        if($this->Model->checkMemberIsNull($data['id']) != 1){
        	echo json_encode(array('status' => false, 'msg' => '查無此會員資料'));
        	return;
		}
        $this->Model->blockade_or_unblockMember($data['id'], 1);
        $this->Model->addBlockingReason($data['id'], $data['reason']);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '封鎖失敗'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '封鎖成功'));
        }
    }

    public function UnblockMember(){
        $data = $this->Form_security_processing($this->input->post());
        $this->db->trans_start();
        $this->Model->blockade_or_unblockMember($data['id'], 0);
        $this->Model->deleteBlockingReason($data['id']);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '解除封鎖失敗'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '解除封鎖成功'));
        }
    }
}
