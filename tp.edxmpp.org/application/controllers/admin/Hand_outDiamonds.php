<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hand_outDiamonds extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Hand_outDiamonds_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/hand_outDiamonds_view', $HTML);
            $this->load->view('window/admin/hand_outDiamonds_window');
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function handOutDiamonds()
    {
        $data = $this->input->post();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '姓名不可為空'),
            1 => array('key' => 'not null', 'msg' => '性別不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $this->db->trans_begin();
        if ($data['id'] != 'all') {
            if ($this->Model->checkMemberIsNull($data['id']) != 1) {
                echo json_encode(array('status' => false, 'msg' => '無此會員資料，請確認後再操作'));
                return;
            }
            $noticeObject = '0';
            $specificObject = $data['id'];
            $acceptID = $data['id'];
            $this->Model->handOutDiamonds($data);
        } else {
            $noticeObject = '1';
            $specificObject = null;
            $acceptID = 'all';
            $this->Model->handOutDiamondsAll($data['point']);

        }
        $date = date('Y-m-d H:i:s');
        $insert = array(
            'nr_sendIdentity' => 'A',
            'nr_noticeObject' => $noticeObject,
            'nr_specificObject' => $specificObject,
            'nr_messageTitle' => $data['messageTitle'],
            'nr_sendMessage' => $data['message'],
            'nr_emailOrNotice' => '2',
            'nr_date' => $date
        );
        $this->Model->addHandOutDiamondsNotice($insert);
        $insert = array(
            'ddr_acceptID' => $acceptID,
            'ddr_point' => $data['point'],
            'ddr_date' => $date,
        );
        $this->Model->addHandOutDiamondsRecord($insert);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '發放鑽石失敗，請重新嘗試'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '發放鑽石成功'));
        }

    }

    public function getMemberData(){
        $data = $this->input->post();
        echo json_encode($this->Model->getMemberData($data['id']));
    }

    public function getHandOutDiamondsRecord(){
        $data = $this->input->post();
        echo json_encode($this->Model->getHandOutDiamondsRecord($data['date']));
    }

    public function getDiamondsRecord(){
        $data = $this->input->post();
        echo json_encode($this->Model->getDiamondsRecord($data['date']));
    }

    public function getHandOutDiamondsRecordSpecific(){
        $data = $this->input->post();
        echo json_encode($this->Model->getHandOutDiamondsRecordSpecific($data['id']));
    }

    public function cancelHandOutDiamonds(){
        $data = $this->input->post();
        $this->db->trans_begin();
        $diamondsData = $this->Model->getRecordData($data['id']);
        if($diamondsData == null){
            echo json_encode(array('status' => false, 'msg' => '查無此紀錄，請刷新重新嘗試'));
            return;
        }

        $this->Model->deductionMemberDiamonds($diamondsData);
        $this->Model->deleteHandOutDiamondsRecord($diamondsData);

        if ($diamondsData->ddr_acceptID != 'all') {

            $noticeObject = '0';
            $specificObject = $diamondsData->ddr_id;
        } else {
            $noticeObject = '1';
            $specificObject = null;
        }
        $date = date('Y-m-d H:i:s');
        $insert = array(
            'nr_sendIdentity' => 'A',
            'nr_noticeObject' => $noticeObject,
            'nr_specificObject' => $specificObject,
            'nr_messageTitle' => $data['messageTitle'],
            'nr_sendMessage' => $data['message'],
            'nr_emailOrNotice' => '2',
            'nr_date' => $date
        );
        $this->Model->addHandOutDiamondsNotice($insert);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '取消發放失敗，請重新嘗試'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '取消放送成功'));
        }
    }
}
