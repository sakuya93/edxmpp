<?php

class admin_Payment_history_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getPlatformEarn(){ //此方法為抓取平台總賺取金額及抽成%數
        return $this->db->select('*')
            ->from('platform_earn')
            ->get()->result();
    }

    public function setSalesCommisstion($per = 0){ //此方法為設定平台抽成%數
        $this->db->query("UPDATE platform_earn SET draw_into = {$per}");
    }

    public function getPaymentHistoryPoint($date = '')
    {
        return $this->db->select('ph_id AS id, m_name AS name, m_photo AS photo, "鑽石" AS project, ph_price AS price, ph_status AS status, ph_date AS date')
            ->from('payment_history')
            ->where('ph_project', 'point')
            ->like('ph_date', $date)
            ->join('member', 'payment_history.m_id = member.m_id', 'inner')
            ->get()->result();
    }

    public function getPaymentHistoryClass($date = '')
    {
        return $this->db->select('ph_id AS id, m_name AS name, m_photo AS photo, "購買課程" AS project, ph_price AS price, ph_drawInto AS drawInto, ph_scale AS scale, ph_status AS status, ph_date AS date')
            ->from('payment_history')
            ->where('ph_project !=', 'point')
            ->like('ph_date', $date)
            ->join('member', 'payment_history.m_id = member.m_id', 'inner')
            ->get()->result();
    }


    public function getClassData($id = '')
    {
        return $this->db->select('t_name AS teacherName, t_photo AS photo, CONCAT(l_id, cf_id) AS courseID, CONCAT(l_actualMovie, cf_name) AS courseName, CONCAT(l_type, cf_type) AS courseType,
        CONCAT(l_thumbnail, cf_thumbnail) AS image, IF(l_id, "resource/image/teacher/film/", "resource/image/teacher/live/") AS imagePath')
            ->from('payment_history')
            ->where('ph_id', $id)
            ->join('live', 'live.l_id = payment_history.ph_project', 'inner', true)
            ->join('courseFilm', 'courseFilm.cf_id = payment_history.ph_project', 'inner', true)
            ->join('teacher', 'teacher.t_id = live.t_id OR teacher.t_id = courseFilm.t_id', 'inner')
            ->get()->result();
    }

    public function clearExpiredPaymentHistory($date = ''){
        return $this->db->where('ph_date <=', $date)->delete('payment_history');
    }

    public function checkOrderIsNull($id = ''){
        return $this->db->select('*')->from('payment_history')->where('ph_id', $id)->get()->num_rows();
    }

    public function getClass($order = ''){
        return ($this->db->select('ph_project')->from('payment_history')->where('ph_id', $order)->get()->row())->ph_project;
    }

    public function getOrderClassData($dataArray = ''){
        return $this->db->select("IFNULL(sc.l_id, sc.cf_id) AS courseID, IFNULL(l_actualMovie, cf_name) AS courseName, IFNULL(l_type, cf_type) AS courseType,
        IFNULL(l_thumbnail, cf_thumbnail) AS image, t_name AS teacherName, t_photo AS teacherPhoto, IF(l_thumbnail, 'resource/image/teacher/live', 'resource/image/teacher/film') AS imagePath")
            ->from('shoppingCart AS sc')
            ->where_in('sc_id', $dataArray)
            ->join('courseFilm', "courseFilm.cf_id = sc.cf_id AND cf_experienceFilm != 'null'", 'left')
            ->join('live', 'live.l_id = sc.l_id', 'left')

            ->join('teacher', 'teacher.t_id = live.t_id OR teacher.t_id = courseFilm.t_id', 'left')
            ->get()->result();
    }

    public function getOrderData($ph_id = ''){
        return $this->db->select('ph_project, ph_price, m_id')->from('payment_history')->where('ph_id', $ph_id)->get()->row();
    }

    public function setAlreadyPaid_sc($sc_id = ''){
     return $this->db->where('sc_id', $sc_id)->update('shoppingCart', array('sc_payStatus' => '1'));
    }

    public function setAlreadyPaid_ph($ph_id = ''){
        return $this->db->where('ph_id', $ph_id)->update('payment_history', array('ph_status' => '1'));
    }

    public function checkPayStatus($ph_id = ''){
        return $this->db->select('*')->from('payment_history')->where('ph_id', $ph_id)->where('ph_status', '1')->get()->num_rows();
    }

    public function updatePersonalDiamondNull($m_id = '', $point = 0){
        return $this->db->where('m_id', $m_id)->update('main', array('points' => $point));
    }

    public function updatePersonalDiamond($m_id = '', $point = 0){
        $this->db->query("UPDATE main SET points = points + {$point} WHERE m_id = '{$m_id}'");
    }

    public function checkPersonalDiamondIsNull($m_id = ''){
        return $this->db->select('*')->from('main')->where('m_id', $m_id)->where('points', null)->get()->num_rows();
    }

    public function checkMemberIsNull($m_id = ''){
        return $this->db->select('*')->from('main')->where('m_id', $m_id)->get()->num_rows();
    }
}