<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class teacher_salary_management extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/teacher_salary_management_model", "Model", TRUE);
    }

    public function index($id = '')
    {
        if (!$this->getlogin())
            redirect(base_url('TPManager'));

        $HTML['sideBarContent'] = $this->getSideBar("");

        $this->load->view('admin/teacher_salary_management_view', $HTML);
        $this->load->view('window/admin/teacher_salary_management_window');
        $this->load->view('window/hint_window');
    }

    public function getSalaryData()
    {
        $data = $this->input->post();

        if ($data['status'] == 0)
            echo json_encode($this->Model->getSalaryDataStatus0());
        else
            echo json_encode($this->Model->getSalaryDataStatus1());
    }

    public function getSalaryDetail()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getSalaryDetail($data['id']));
    }

    public function updateSalaryStatus()
    {
        $data = $this->input->post();
        $price = $this->Model->getSalaryPrice($data['id']);
        if($price == null){
            echo json_encode(array('status' => false, 'msg' => '找不到此老師資料'));
            return;
        }
        $this->db->trans_begin();

        $this->Model->updateSalaryStatus($data['id'], $price);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '更新狀態失敗'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '更新狀態成功'));
        }
    }
}
