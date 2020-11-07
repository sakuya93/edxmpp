<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency_conversion extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Taipei');
        $this->load->model("admin/admin_currency_conversion_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $data = $this->Model->getStatus();
            @$data->startStatus = @$data->startStatus == 0 ? "" : "checked";

            $data->sideBarContent = $this->getSideBar("");
            $this->load->view('admin/currency_conversion_view', $data);
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function getCurrencyConversion()
    {
        echo json_encode($this->Model->getCurrencyConversion());
    }

    public function update_exchange_rate()
    {
        include('simple_html_dom.php');

        //原本的
//        $currency = array('TWD', 'USD', 'CNY', 'JPY', 'AUD', 'EUR', 'HKD', 'GBP', 'CAD', 'THB', 'KRW');
//        $insert = array();
//        $x = 0;
//        $y = 0;
//        $html = file_get_html("https://www.findrate.tw/converter.php");
//        foreach ($html->find('tbody tr td') as $index => $e) {
//            if ($index != 0 & $index % 11 == 0)
//                $x++;
//            $insert[] = array('cc_id' => $currency[$x], 'cc_toid' => $currency[$y], 'cc_exchangeRate' => $e->plaintext);
//            $y++;
//            if ($y == 11)
//                $y = 0;
//        }

        $currency = array('TWD', 'VND', 'MYR', 'USD');

        $insert = array();
        $x = 0;
        $y = 0;

        while ($x != 4) {
            $convert_currency = $currency[$x] . "/" . $currency[$y];

            $html = file_get_html("https://www.findrate.tw/converter/{$convert_currency}/1/");
            foreach ($html->find('tbody tr td') as $index => $e) {
                if ($index != 3) continue; //如果多加貸幣 此行不必改變

                //貸幣取值
                if ($x == $y) { //相同貸幣1
                    $temp = 1;
                } else {
                    $temp = substr($e->plaintext, strpos($e->plaintext, "=") + 2);
                    $temp = substr($temp, 0, strpos($temp, " "));
                }

                $insert[] = array('cc_id' => $currency[$x], 'cc_toid' => $currency[$y], 'cc_exchangeRate' => $temp);
            }
            $y++;
            if ($y == 4) {
                $x++;
                $y = 0;
            }
        }

        if ($this->Model->update_exchange_rate($insert))
            echo json_encode(array('status' => true, 'msg' => '更新匯率成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '更新匯率失敗'));
    }

    public function automatic_update_exchange_rate_controller()
    {
        if ($this->Model->checkAutomatic()) {
            if ($this->Model->openDownAutomatic()) {
                $this->automatic_update_exchange_rate();
            } else
                echo json_encode(array('status' => false, 'msg' => '開啟自動更新失敗'));
        } else {
            if ($this->Model->shoutDownAutomatic())
                echo json_encode(array('status' => true, 'msg' => '關閉自動更新成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '關閉自動更新失敗'));
        }
    }

    public function automatic_update_exchange_rate()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        include('simple_html_dom.php');
        echo json_encode(array('status' => true, 'msg' => '開啟自動更新成功'));
        while (!$this->Model->checkAutomatic()) {
            $date = $this->Model->getUPdateExchangeRateDate();

            if (date('Y-m-d') != substr($date, 0, 10)) {
                $currency = array('TWD', 'VND', 'MYR', 'USD');
                $insert = array();
                $x = 0;
                $y = 0;

                while ($x != 4) {
                    $convert_currency = $currency[$x] . "/" . $currency[$y];

                    $html = file_get_html("https://www.findrate.tw/converter/{$convert_currency}/1/");
                    foreach ($html->find('tbody tr td') as $index => $e) {
                        if ($index != 3) continue; //如果多加貸幣 此行不必改變

                        //貸幣取值
                        if ($x == $y) { //相同貸幣1
                            $temp = 1;
                        } else {
                            $temp = substr($e->plaintext, strpos($e->plaintext, "=") + 2);
                            $temp = substr($temp, 0, strpos($temp, " "));
                        }

                        $insert[] = array('cc_id' => $currency[$x], 'cc_toid' => $currency[$y], 'cc_exchangeRate' => $temp);
                    }
                    $y++;
                    if ($y == 4) {
                        $x++;
                        $y = 0;
                    }
                }

                $this->Model->update_exchange_rate($insert);
            }
            $this->db->flush_cache();
            sleep(3600);
        }
    }
}
