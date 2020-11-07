<?php

class admin_Message_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkMemberIsNull($m_id = ''){
        return $this->db->select('*')->from('member')->where('m_id', $m_id)->get()->num_rows();
    }

    public function checkContinuousContact($data = '')
    {
        return $this->db->select('*')
            ->from('admin_contact_window')
            ->where('acw_MID', $data['acw_MID'])
            ->like('acw_date', date("Y-m-d H:i"))
            ->get()->num_rows();
    }

    public function addAdminContact_a($insert = ''){
        return $this->db->insert('admin_contact_window', $insert);
    }

    public function getMemberData($m_id = ''){
        return $this->db->select('m_name AS name, m_photo AS photo')->from('member')->where('m_id', $m_id)->get()->row();
    }

    public function getAdminContact($index = 0){
        $start = $index;
        $end = ($index+1) * 5;
        $select = $this->db->select("m_name AS memberName, t_name AS teacherName, acw_MID AS memberID, teacher.t_id AS teacherID,
         m_photo AS memberPhoto, acw_message AS message, acw_date AS date,
        who_say, acw_haveRead AS haveRead, ROW_NUMBER() OVER (PARTITION BY acw_MID ORDER BY acw_date DESC) AS sn")
            ->from("admin_contact_window")
            ->join('member', 'member.m_id = acw_MID', 'left')
            ->join('main', 'main.m_id = acw_MID', 'left')
            ->join('teacher', 'teacher.t_id = main.t_id', 'left')
            ->get_compiled_select();
        return $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT $start, $end")->result();
    }

    public function getAdminContactDetailHaveRead($m_id = ''){
        return $this->db->where('acw_MID', $m_id)->where('who_say', 'M')->update('admin_contact_window', array('acw_haveRead' => '1'));
    }

    public function getAdminContactDetail($m_id = '', $index = 0){
        return $this->db->select('acw_id AS id, m_name AS memberName , t_name AS teacherName, acw_MID AS memberID, teacher.t_id AS teacherID,
        m_photo AS photo, acw_message AS message, acw_date AS date, who_say')
            ->from('admin_contact_window')
            ->where('acw_MID', $m_id)
            ->join('member', 'member.m_id = acw_MID', 'left')
            ->join('main', 'main.m_id = acw_MID', 'left')
            ->join('teacher', 'teacher.t_id = main.t_id', 'left')
            ->order_by('acw_date', 'DESC')
            ->limit(5, $index * 5)
            ->get()->result();
    }

    public function getNewAdminContactDetail($data = '')
    {
        $returnData = $this->db->select("acw_id AS id, acw_message AS message, acw_date AS date, IF(who_say = 'A', '1', '0') AS who_say,
        m_name AS name, m_photo AS photo")
            ->from('admin_contact_window')
            ->where('acw_MID', $data['memberID'])
            ->join('member', 'member.m_id = admin_contact_window.acw_MID', 'inner')
            ->order_by('acw_date', 'DESC')
            ->limit(1, 0)
            ->get_compiled_select();

        return $this->db->query("SELECT S1.* FROM({$returnData}) AS S1 WHERE S1.who_say = '0'")->result();

    }

    public function updateNewAdminContactDetail($data = ''){
        return $this->db->where('acw_id', $data['id'])->where('acw_haveRead', '0')->update('admin_contact_window', array('acw_haveRead' => '1'));
    }
}