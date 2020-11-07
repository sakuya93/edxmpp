<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_management extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Report_management_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/report_management_view', $HTML);
        } else
            redirect(base_url('TPManager'));
    }

    public function getClassReport(){
        $data = $this->input->post();
        echo json_encode($this->Model->getClassReport($data['date']));
    }

    public function getClassReportDetail(){
        $data = $this->input->post();
        echo json_encode($this->Model->getClassReportDetail($data['reported']));
    }

    public function getStudentReport() {
        $data =$this->input->post();
        echo json_encode($this->Model->getStudentReport($data['date']));
    }

    public function getStudentReportDetail(){
        $data = $this->input->post();
        echo json_encode($this->Model->getStudentReportDetail($data['reported']));
    }

    public function getReportRecord(){
        $data = $this->input->post();
        echo json_encode($this->Model->getReportRecord($data['date']));
    }

    public function getReportRecordDetail(){
        $data = $this->input->post();
        echo json_encode($this->Model->getReportRecordDetail($data['report']));
    }
}
