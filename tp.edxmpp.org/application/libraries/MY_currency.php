<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class My_currency extends CI_Model {
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
    public function checkCurrency($currency = ''){
        if($this->db->select('*')->from('currency_conversion')->where('cc_id', $currency)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

}