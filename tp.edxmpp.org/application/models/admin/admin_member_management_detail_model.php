<?php
class admin_member_management_detail_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkMemberIsNUll($m_id = ''){
        return $this->db->select('*')->from('member')->where('m_id', $m_id)->get()->num_rows();
    }

    public function getDetailedMemberData($m_id = ''){//admin的model前面要加admin
        return $this->db->select('m_type AS type, m_name AS name, m_email AS email, m_date AS date,
		 m_timezone AS timezone, m_country AS country, m_motherTongue AS motherTongue, m_city AS city,
		 m_photo AS photo, points')
            ->from('member')
            ->where('member.m_id', $m_id)
            ->join('main', 'main.m_id = member.m_id', 'inner')
            ->get()->row();
    }

    public function getMemberStatus($m_id = ''){//取得會員封鎖狀態
        return $this->db->select('m_type AS type')
            ->from('member')
            ->where('m_id', $m_id)
            ->get()->row();
    }

    public function getOwnCourse($m_id){
        return $this->db->select("IFNULL(l_actualMovie, cf_name) AS courseName, IF(live.l_id, '直播', '影片') AS courseType,
        IFNULL(l_thumbnail, cf_thumbnail) AS courseImage, t_name AS teacherName, t_photo AS teacherPhoto, sc_payStatus AS payStatus")
            ->from('shoppingCart')
            ->where('m_id', $m_id)
            ->join('live', 'live.l_id = shoppingCart.l_id', 'left')
            ->join('courseFilm', 'courseFilm.cf_id = shoppingCart.cf_id', 'left')
            ->join('teacher', 'teacher.t_id = shoppingCart.t_id', 'left')
            ->group_by('shoppingCart.sc_id')
            ->get()->result();
    }
}