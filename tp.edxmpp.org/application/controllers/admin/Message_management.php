<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_management extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Message_management_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/message_management_view', $HTML);
            $this->load->view('window/admin/designated_contact_window');
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function addAdminContact_a()
    {
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '傳送訊息不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->Model->checkMemberIsNull($data['id']) != 1) {
            echo json_encode(array('status' => false, 'msg' => '無此會員資料，請刷新頁面重新嘗試'));
            return;
        }
        $insert = array(
            'who_say' => 'A',
            'acw_MID' => $data['id'],
            'acw_message' => $data['message'],
            'acw_date' => date("Y/m/d H:i:s"),
            'acw_haveRead' => '0',
        );

        if ($this->Model->checkContinuousContact($insert) > 20) {
            echo json_encode(array('status' => false, 'msg' => '請放慢傳送訊息的速度'));
            return;
        }

        if ($this->Model->addAdminContact_a($insert))
            echo json_encode(array('status' => true, 'msg' => '傳送訊息成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '傳送訊息失敗'));

    }

    public function getMemberData()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getMemberData($data['id']));
    }

    public function getAdminContact()
    {
        $data = $this->input->post();

        echo json_encode($this->Model->getAdminContact($data['index']));
    }

    public function getAdminContactDetail(){
        $data = $this->input->post();
        if($this->Model->getAdminContactDetailHaveRead($data['id'])) {
            echo json_encode($this->Model->getAdminContactDetail($data['id'], $data['index']));
        }else{
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
        }
    }

    public function getNewAdminContactDetail(){
        $data = $this->Form_security_processing($this->input->post());
        set_time_limit(0);//無限請求超時時間
        $i = 0;

        while (true) {
            sleep(0.5);//0.5秒
            $i++;
            $returnData = $this->Model->getNewAdminContactDetail($data);


            if ($returnData != null) {
                if ($returnData[0]->id != $data['id']) {
                    $this->Model->updateNewAdminContactDetail($data);
                    echo json_encode(array('status' => true, 'contact' => $returnData));
                    break;
                }
            }
            if ($i == 250) {
                echo json_encode(array('status' => false));
                break;
            }
        }
    }
}
