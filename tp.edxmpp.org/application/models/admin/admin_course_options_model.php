<?php

class admin_course_options_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getdata()
    {
        return $this->db->select('*')->from('classOption')->get()->result();
    }

    public function addOption($insert = ""){
        return $this->db->insert('classOption', $insert);
    }

    public function deleteOption($data = ""){
        $this->db->trans_begin();
        for($i = 0; $i < count($data); ++$i) {
            $this->db->where('id', $data[$i]['id'])->delete('classOption');
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            return true;
        }
        else {
            $this->db->trans_rollback();
            return false;
        }

    }

}