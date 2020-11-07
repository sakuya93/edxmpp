<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_center extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("student_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();
            $this->checkOneLogin();
            $this->load->view('student/help_center_view');
        } else
            redirect(base_url('home'));
    }

}

