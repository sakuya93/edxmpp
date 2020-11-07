<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teams_liveManagement extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Teams_liveManagement_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()){
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/teams_liveManagement_view', $HTML);
            $this->load->view('window/admin/teams_liveManagement_detail_window');
            $this->load->view('window/admin/MS_API_window');
            $this->load->view('window/admin/hint_window');
        }
        else
            redirect(base_url('TPManager'));
    }

    public function getLiveMatchTime($day = ''){
        echo json_encode($this->Model->getLiveMatchTime($day));
    }

    public function getLiveMatchTimeDetail($id = ''){
        echo json_encode($this->Model->getLiveMatchTimeDetail($id));
    }

    public function getTeamsLiveData($id = ''){
        echo json_encode($this->Model->getTeamsLiveData($id));

    }

    public function completeMeetingLayout(){
        $data = $this->input->post();
        if($this->Model->completeMeetingLayout($data['id']))
            echo json_encode(array('status' => true, 'msg' => '會議佈置完成設定成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '會議佈置完成設定失敗'));
    }
}
