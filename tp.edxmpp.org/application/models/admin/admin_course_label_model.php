<?php

class admin_course_label_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkLabel($label = ''){
        return $this->db->select('*')->from('course_label')->where('label', $label)->get()->num_rows();
    }

    public function addLabel($label = ''){
        return $this->db->insert('course_label', array('label' => $label));
    }

    public function getLabel(){
        return $this->db->select('*')->from('course_label')->get()->result();
    }

    public function deleteLabel($label = ''){
        $this->db->trans_begin();
        for($i = 0; $i < count($label); ++$i) {
            $this->db->where('label', $label[$i]['label'])->delete('course_label');
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