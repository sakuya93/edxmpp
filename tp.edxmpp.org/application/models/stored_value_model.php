<?php

class stored_value_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkMemberEmailStatus(){
        return $this->db->select('*')->from('member')->where('member.m_id', $_SESSION['Mid'])->join('main', 'main.m_id = member.m_id', 'inner')
            ->where('emailStatus', '1')->get()->num_rows();
    }

    public function storedValueSend($insert = '', $ph_id = ''){
        if($ph_id != null)
            return $this->db->where('ph_id', $ph_id->ph_id)->update('payment_history', $insert);
        else
            return $this->db->insert('payment_history', $insert);
    }

    public function getTNO_data($TNO = ''){
        return ($this->db->select('ph_price')->from('payment_history')->where('ph_id', $TNO)->get()->row())->ph_price;
    }

    public function updatePoint($point = ''){
        $oldPoint = ($this->db->select('points')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row())->points;
        if($oldPoint == null)
            $oldPoint = 0;
        $point = $oldPoint + $point;
        return $this->db->where('m_id', $_SESSION['Mid'])->update('main', array('points' => $point));
    }

    public function updatePaymentHistoryStatus($TNO = ''){
        return $this->db->where('ph_id', $TNO)->update('payment_history', array('ph_status' => '1'));
    }

    public function getOldOrder(){
        return $this->db->select('ph_id')->from('payment_history')->where('m_id', $_SESSION['Mid'])->where('ph_status', '0')->where('ph_project', 'point')
        ->get()->row();
    }
}