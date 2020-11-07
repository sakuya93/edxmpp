<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_room extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("live_room_model", "Model", TRUE);
    }

    public function index($l_id = '')
    {
        if ($this->getlogin()) {

            $this->load->view('student/live_room_view');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }
}
