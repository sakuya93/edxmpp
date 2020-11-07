<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hand_outGolds extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Hand_outGolds_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/hand_outGolds_view', $HTML);
            $this->load->view('window/admin/hand_outGolds_window');
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function handOutGolds()
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

        if ($data['id'] == 'all_random') { //隨機發放
            $lucky_guy = $this->Model->getRandomMember($data['quota']);
            $noticeObject = '1';
            $date = date('Y-m-d H:i:s');

            for ($i = 0; $i < count($lucky_guy); $i++) {
                $data['id'] = $lucky_guy[$i]->id;
                $specificObject = $data['id'];
                $acceptID = $data['id'];

                $this->Model->handOutGolds($data);

                $insert = array(
                    'nr_sendIdentity' => 'A',
                    'nr_noticeObject' => $noticeObject,
                    'nr_specificObject' => $specificObject,
                    'nr_messageTitle' => $data['messageTitle'],
                    'nr_sendMessage' => $data['message'],
                    'nr_emailOrNotice' => '2',
                    'nr_date' => $date
                );

                $this->Model->addHandOutGoldsNotice($insert);
                $insert = array(
                    'grr_acceptID' => $acceptID,
                    'grr_point' => $data['gold'],
                    'grr_date' => $date,
                );
                $this->Model->addHandOutGoldsRecord($insert);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '發放金幣失敗，請重新嘗試'));
            } else {
                $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '發放金幣成功'));
            }
            return; //因為隨機發放會在此區塊直接做完，所以這邊直接結束程式
        } else if ($data['id'] != 'all') {
            if ($this->Model->checkMemberIsNull($data['id']) != 1) {
                echo json_encode(array('status' => false, 'msg' => '無此會員資料，請確認後再操作'));
                return;
            }
            $noticeObject = '0';
            $specificObject = $data['id'];
            $acceptID = $data['id'];
            $this->Model->handOutGolds($data);
        } else {
            $noticeObject = '1';
            $specificObject = null;
            $acceptID = 'all';
            $this->Model->handOutGoldsAll($data['gold']);

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

        $this->Model->addHandOutGoldsNotice($insert);
        $insert = array(
            'grr_acceptID' => $acceptID,
            'grr_point' => $data['gold'],
            'grr_date' => $date,
        );
        $this->Model->addHandOutGoldsRecord($insert);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '發放金幣失敗，請重新嘗試'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '發放金幣成功'));
        }

    }

    public function getMemberData()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getMemberData($data['id']));
    }

    public function getHandOutGoldsRecord()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getHandOutGoldsRecord($data['date']));
    }

    public function getGoldsRecord()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getGoldsRecord($data['date']));
    }

    public function getHandOutGoldsRecordSpecific()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getHandOutGoldsRecordSpecific($data['id']));
    }

    public function cancelHandOutGolds()
    {
        $data = $this->input->post();
        $this->db->trans_begin();
        $GoldsData = $this->Model->getRecordData($data['id']);
        if ($GoldsData == null) {
            echo json_encode(array('status' => false, 'msg' => '查無此紀錄，請刷新重新嘗試'));
            return;
        }

        $this->Model->deductionMemberGolds($GoldsData);
        $this->Model->deleteHandOutGoldsRecord($GoldsData);

        if ($GoldsData->grr_acceptID != 'all') {

            $noticeObject = '0';
            $specificObject = $GoldsData->grr_id;
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
        $this->Model->addHandOutGoldsNotice($insert);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '取消發放失敗，請重新嘗試'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '取消放送成功'));
        }
    }
}
