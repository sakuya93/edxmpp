<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test2 extends Infrastructure
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model("test_model", "Model", TRUE);
    }

    public function index()
    {
        include('simple_html_dom.php');

//        $html = file_get_html('https://tw.exchange-rates.org/MajorRates.aspx'); //較多國家ㄉ轉換率
        $html = file_get_html('https://www.findrate.tw/converter/TWD/USD/1/');

        $from = "USD"; //從
        $from_index = 0; //從的索引

        $convert_To = "TWD"; //轉換成
        $convert_To_index = 0; //轉換成的索引

        foreach ($html->find('table') as $e) {
//            foreach ($html->find('thead .text-nowrap') as $from_key => $e_from) {
//                echo $e_from . '<br>';
//                echo $from . '<br>';
//                if (strcmp($from, $e_from) != -1) $from_index = $from_key;
//            }
//            var_dump($from_index);
//            echo $from_index . '<br>';
//            foreach ($html->find('tr a[title=USD]') as $w_from) {
//                echo $w_from . '<br>';
////                echo $w_from -> td . '<br>';
//            }
//            var_dump(strcmp($e,"美元"));
//            echo $e . '<br>';

            $x = $e->children(2);
            echo substr((string)$x, strpos(">",(string)$x));
//            echo $e->next_sibling ();


        }

    }
}
