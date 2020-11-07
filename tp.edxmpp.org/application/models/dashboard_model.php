<?php

class dashboard_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getClassData($id = ''){
        return $this->db->select('L.l_actualMovie AS liveName, l_type AS type, t_name AS teacherName, ce_comment AS comment, ce_level AS score')
            ->from('courseevaluation AS C')
            ->where('C.m_id', $id)
            ->where('C.t_id !=', 'null')
            ->join('live AS L', 'C.l_id = L.l_id', 'inner')
            ->join('teacher AS T', 'C.t_id = T.t_id', 'left')
            ->group_by('C.t_id')
            ->get()->result();
    }

    public function getFilmData($id = ''){
        return $this->db->select('CF.cf_experienceFilmName AS filmName, T.t_name AS teacherName')
            ->from('shoppingcart AS SC')
            ->where('SC.m_id', $id)
            ->where('SC.cf_id !=', '')
            ->join('coursefilm AS CF', 'SC.cf_id = CF.cf_id', 'inner')
            ->join('teacher AS T', 'SC.t_id = T.t_id', 'left')
            ->group_by('SC.t_id')
            ->get()->result();
    }

    public function getMemberData($id = ''){
        return $this->db->select('m_name AS name, email_date AS registeredDate, t_id AS teacherID, m_speakLanguage AS speakLanguage, m_country AS country, m_photo AS photo')
            ->from('main')
            ->where('main.m_id', $id)
            ->join('member', 'main.m_id = member.m_id', 'left')
            ->get()->result();
    }

    public function checkMember($id = ''){
        if($this->db->select('*')->from('member')->where('m_id', $id)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }
}