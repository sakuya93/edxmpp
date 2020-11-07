<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class member_management_detail extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_member_management_detail_model", "Model", TRUE);
    }

    public function index($m_id = '')
    {
        if (!$this->getlogin())
            redirect(base_url('TPManager'));
        if ($this->Model->checkMemberIsNUll($m_id) != 1)
            redirect(base_url('member_management'));
        $result = $this->Model->getDetailedMemberData($m_id);

        $HTML = array(
            'm_id' => $m_id
        );

        $memberStatus = $this->Model->getMemberStatus($m_id);
        $HTML['memberStatus'] = @$memberStatus->type == 0 ? "checked" : "";

        //基本資料
        $HTML['photo'] = @$result->photo == null ? 'noPhoto.jpg' : $result->photo;
        $HTML['name'] = @$result->name;
        $HTML['date'] = @$result->date;
        $HTML['country'] = @$result->country;
        $HTML['motherTongue'] = @$result->motherTongue;
        $HTML['email'] = @$result->email;
        $HTML['city'] = @$result->city;
        $HTML['timezone'] = @$result->timezone;
        $HTML['points'] = @$result->points;

        $this->load->view('admin/member_management_detail_view', $HTML);
        $this->load->view('window/admin/block_member_window');
        $this->load->view('window/hint_window');
    }

    public function getOwnCourse($m_id = ''){
        echo json_encode($this->Model->getOwnCourse($m_id));
    }
}
