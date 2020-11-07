<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_history extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Payment_history_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/payment_history_view', $HTML);
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function getPlatformEarn() //此方法為抓取平台總賺取金額及抽成%數
    {
        echo json_encode($this->Model->getPlatformEarn());
    }

    public function setSalesCommisstion() //此方法為設定平台抽成%數
    {
        $data = $this->input->post();
        $this->Model->setSalesCommisstion($data['per']);
    }

    public function getPaymentHistoryPoint()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getPaymentHistoryPoint($data['date']));
    }

    public function getPaymentHistoryClass()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getPaymentHistoryClass($data['date']));
    }

    public function getClassData()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getClassData($data['id']));
    }

    public function clearExpiredPaymentHistory()
    {
        $date = date('Y-m-d H:i:s', strtotime('-1 mouth'));
        if ($this->Model->clearExpiredPaymentHistory($date))
            echo json_encode(array('status' => true, 'msg' => '清除過期付款單成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '清除過期付款單失敗'));
    }

    public function checkingOrder()
    {
        $data = $this->input->post();

        if ($this->Model->checkOrderIsNull($data['order']) != 1) {
            echo json_encode(array('status' => false, 'msg' => '查無此訂單資料，請刷新頁面重新嘗試'));
            return;
        }
        if($this->Model->checkPayStatus($data['order']) == 1){
            echo json_encode(array('status' => false, 'msg' => '此訂單已為付款狀態'));
            return;
        }
        $ph_data = $this->Model->getOrderData($data['order']);

        $this->db->trans_begin();
        if ($ph_data->ph_project != "point") {
            $project = mb_split("、", $ph_data->ph_project);
            foreach ($project AS $temp) {
                $this->Model->setAlreadyPaid_sc($temp);
            }
        }else{
            if($this->Model->checkMemberIsNull($ph_data->m_id) != 1){
                echo json_encode(array('status' => false, 'msg' => '找不到會員資料'));
                return;
            }
            $point = array(
                "100" => "300",
                "250" => "750",
                "500" => "1500",
                '1000' => '3000',
                '2500' => '7500',
            );
            if (!isset($point["{$ph_data->ph_price}"])) {
                echo json_encode(array('status' => false, 'msg' => '無此價格選項，請刷新頁面重新嘗試'));
                return;
            }
            if($this->Model->checkPersonalDiamondIsNull($ph_data->m_id) == 1)
                $this->Model->updatePersonalDiamondNull($ph_data->m_id, $point["{$ph_data->ph_price}"]);
            else
                $this->Model->updatePersonalDiamond($ph_data->m_id, $point["{$ph_data->ph_price}"]);
        }
        $this->Model->setAlreadyPaid_ph($data['order']);

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => false, 'msg' => '更新付款狀態失敗'));
            $this->db->trans_rollback();
        } else {
            echo json_encode(array('status' => true, 'msg' => '更新付款狀態成功'));
            $this->db->trans_commit();
        }
    }


    public function getOrderClassData()
    {
        $data = $this->input->post();
        $class = $this->Model->getClass($data['order']);
        $classArray = mb_split("、", $class);
        echo json_encode($this->Model->getOrderClassData($classArray));
    }
}
