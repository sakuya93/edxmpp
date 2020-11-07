<?php

class admin_Teams_account_issues_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getTeacherData($type = 0)
    {
        if ($type == 0)
            return $this->db->select('t_name AS teacherName, teacher.t_id AS id, t_teamsAccount AS teamsAccount, application_key AS applicationKey, list_key AS listKey')
                ->from('teacher')
                ->where('t_teamsAccount', null)
                ->join('main', 'teacher.t_id = main.t_id', 'inner')
                ->where('teacherStatus', '1')
                ->get()->result();
        else if ($type == 1)
            return $this->db->select('t_name AS teacherName, teacher.t_id AS id, t_teamsAccount AS teamsAccount, application_key AS applicationKey, list_key AS listKey')
                ->from('teacher')
                ->where('t_teamsAccount !=', null)
                ->join('main', 'teacher.t_id = main.t_id', 'inner')
                ->where('teacherStatus', '1')
                ->get()->result();
    }

    public function freedAccount($t_id = '', $update = '')
    {
        return $this->db->where('t_id', $t_id)->update('teacher', $update);
    }

    public function checkTeacherReview($t_id = '')
    {
        return $this->db->select('*')->from('teacher')->where('teacher.t_id', $t_id)->join('main', 'teacher.t_id = main.t_id')->where('teacherStatus', '1')->get()->num_rows();
    }

    public function getTeacherEmail($t_id = ''){
        return ($this->db->select('m_email')->from('main')->where('t_id', $t_id)->join('member', 'main.m_id = member.m_id')->get()->row())->m_email;
    }
}