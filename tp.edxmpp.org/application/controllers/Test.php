<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Infrastructure
{
    private $currencies = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->model("test_model", "Model", TRUE);
    }

    public function index()
    {
/*        include('simple_html_dom.php');
        $data = $this->input->get();
        $html = file_get_html("https://www.findrate.tw/converter/{$data['x']}/{$data['y']}/1/");

        $num = 0;
        foreach ($html->find('span[style]') as $e) {
            ++$num;
            if($num == 3)
                echo substr($e, strrpos($e, '=')+1, -11);
        }*/

        $this->load->view('test_view');
    }
}
