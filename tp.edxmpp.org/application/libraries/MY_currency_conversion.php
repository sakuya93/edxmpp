<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_currency_conversion extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
    public function getExchangeRate($currency = ''){

    }
}