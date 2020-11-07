<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_management extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/Admin_management_model", "Model", TRUE);
    }

    public function index()
    {
        if($this->getlogin()){
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/admin_management_view', $HTML);
        }
        else
            redirect(base_url('TPManager'));
    }

}
