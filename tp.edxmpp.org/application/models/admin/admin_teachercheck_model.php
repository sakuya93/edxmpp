<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_teachercheck_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getNotCheck()
    {
        return $this->db->distinct()->select('teacher.t_id as id, teacher.t_name as name, teacher.t_country as country, teacher.t_speakLanguage as speakLanguage')->from('main')->where('main.t_id !=', null)->where('teacher.t_id !=', "")->where('main.teacherStatus', null)->or_where('main.teacherStatus', 0)
            ->join('teacher', 'teacher.t_id = main.t_id', 'left')
            ->get()->result();
    }

    public function getCheck()
    {
        return $this->db->select('teacher.t_id as id, t_name as name, t_country as country, t_speakLanguage as speakLanguage')->from('main')->where('teacherStatus', 1)
            ->join('teacher', 'teacher.t_id = main.t_id', 'left')
            ->get()->result();
    }

    public function getBanCheck(){
        return $this->db->select('teacher.t_id as id, t_name as name, t_country as country, t_speakLanguage as speakLanguage')->from('main')->where('teacherStatus', 2)
            ->join('teacher', 'teacher.t_id = main.t_id', 'left')
            ->get()->result();
    }

    public function getDesignatedAdministrator(){
        return $this->db->select('teacher.t_id as id, t_name as name, t_country as country, t_speakLanguage as speakLanguage')
            ->from('main')
            ->where('designated_administrator', 1)
            ->join('teacher', 'main.t_id = teacher.t_id', 'inner')
            ->get()->result();
    }
    public function checkPass($id = '')
    {
        if ($this->db->update('main', array('teacherStatus' => '1'), array('t_id' => $id)))
            return true;
        else
            return false;
    }

    public function checkBanBecomeTeacher($data = '')
    {
        if ($this->db->select('*')->from('main')->where('t_id', $data)->where('teacherStatus', '2')->get()->num_rows() > 0)
            return false;
        else
            return true;
    }

    public function banBecomeTeacher($id = '')
    {
        if ($this->db->update('main', array('teacherStatus' => '2'), array('t_id' => $id)))
            return true;
        else
            return false;
    }

    public function cancelBanBecomeTeacher($id = ''){
        if ($this->db->update('main', array('teacherStatus' => '0'), array('t_id' => $id)))
            return true;
        else
            return false;
    }

    public function cancelTeacherIdentity($id = ''){
        if ($this->db->update('main', array('teacherStatus' => '0'), array('t_id' => $id)))
            return true;
        else
            return false;
    }

    public function setDesignatedAdministrator($t_id = ''){
        return $this->db->where('t_id', $t_id)->update('main', array('designated_administrator' => 1));
    }
}