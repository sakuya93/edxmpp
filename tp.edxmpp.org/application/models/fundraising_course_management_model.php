<?php

class fundraising_course_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function check_FundraisingCourseRepeat($courseName = '', $type = ''){
        if($type = 0){
            if($this->db->select('*')->from('fundraising_course')->where('fc_courseName', $courseName)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0
                & $this->db->select('*')->from('live')->where('l_actualMovie', $courseName)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0)
                return true;
        }else{
            if($this->db->select('*')->from('fundraising_course')->where('fc_courseName', $courseName)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0
                & $this->db->select('*')->from('coursefilm')->where('cf_name', $courseName)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0)
                return true;
        }

    }

    public function getFundraisingCourseData($id = ''){
        return $this->db->select('*')
            ->from('fundraising_course')
            ->where('fc_id', $id)
            ->get()->result();
    }

    public function addFundraisingCourse($insert = ''){
        return $this->db->insert('fundraising_course', $insert);
    }

    public function editFundraisingCourse ($update = '', $fc_id = ''){
        return $this->db->where('fc_id', $fc_id)->where('t_id', $_SESSION['Tid'])->update('fundraising_course', $update);
    }

    public function getOrdImage($fc_id = ''){
        return $this->db->select('fc_image AS name')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row();
    }

    public function checkFundraisingCourseEditAllowed($fc_id = ''){
        if($this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_conversionStatus', 1)->get()->num_rows() > 0)
            return true;
        return false;
    }

    public function getFundraisingCourseStatus($fc_id = ''){
        return $this->db->select('fc_status')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row();
    }

    public function checkFundraisingCourseIsNull($fc_id = ''){
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }
}