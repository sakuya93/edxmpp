<?php

class student_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function completePersonalInformation(){
        return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->get()->num_rows();
    }
}