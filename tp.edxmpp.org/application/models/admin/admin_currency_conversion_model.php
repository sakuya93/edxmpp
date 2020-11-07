<?php

class admin_currency_conversion_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
    public function __construct()
    {
        date_default_timezone_set('Asia/Taipei');
    }
    public function getCurrencyID(){
        return $this->db->select('cc_id')->from('currency_conversion')->get()->result();
    }

    public function update_exchange_rate($insert = ''){
        $this->db->trans_begin();
        $this->db->empty_table('currency_conversion');
        $this->db->insert_batch('currency_conversion', $insert, 'cc_id cc_toid');
        $this->db->update('currency_conversion_controller', array('ccc_date' => date('Y-m-d H:i:s')));
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();
            return true;
        }
        else
        {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function getStatus(){
        return $this->db->select('ccc_startStatus AS startStatus, ccc_date AS date')->from('currency_conversion_controller')->get()->row();
    }

    public function getCurrencyConversion(){
        return $this->db->select('cc_id AS currency, cc_toid AS toCurrency, cc_exchangeRate AS exchangeRate')->from('currency_conversion')->get()->result();
    }

    public function checkAutomatic(){
        if($this->db->select('*')->from('currency_conversion_controller')->where('ccc_startStatus', 0)->get()->num_rows() != 0)
            return true;
        else
            return false;
    }

    public function openDownAutomatic(){
        return $this->db->where('ccc_startStatus', 0)->update('currency_conversion_controller', array('ccc_startStatus' => 1));
    }

    public function shoutDownAutomatic(){
        return $this->db->where('ccc_startStatus', 1)->update('currency_conversion_controller', array('ccc_startStatus' => 0));
    }

    public function getUPdateExchangeRateDate(){
        return ($this->db->select('ccc_date')->from('currency_conversion_controller')->get()->row())->ccc_date;
    }
}