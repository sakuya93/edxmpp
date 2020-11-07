<?php

class fundraisingCourse_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkEndTime($fc_id = ''){
        if($this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_endTime <', date("Y-m-d H:i:s"))->get()->num_rows() == 1)
            return true;
        else
            return false;
    }

    public function getContactWindow($t_id = ''){
        return $this->db->select('cw_id AS id, cw_Tid AS id2, id AS id3')->from('contact_window')->where('cw_TID', $t_id)->where('cw_MID', $_SESSION['Mid'])->order_by('cw_date', 'DESC')->get()->row();
    }


    public function getFundraisingCourseData($id = '', $currency = 'TWD'){
        return $this->db->select('fundraising_course.t_id AS id1, fc_id AS id2, fc_courseName AS courseName, fc_hours AS hours, fc_type AS type, fc_courseIntroduction AS introduction,
        fc_currency AS currency, FORMAT((fc_normalPrice * cc_exchangeRate), 2) AS normal_price, FORMAT((fc_fundraisingPrice * cc_exchangeRate), 2) AS fundraising_price, fc_expectedNumber AS expectedNumber, fc_remainingNumber AS remainingNumber,
        fc_endTime AS endTime, t_photo AS image, fc_status AS status, t_name AS teacherName, t_speakLanguage AS speakLanguage, t_country AS country,
        t_age AS age, t_sex AS sex, teacherStatus AS teacher_status, fc_filmUrl AS film_url')
            ->from('fundraising_course')
            ->where('fc_id', $id)
            ->join('teacher', 'fundraising_course.t_id = teacher.t_id', 'left')
            ->join('main', 'main.t_id = fundraising_course.t_id', 'left')
            ->join('currency_conversion', "currency_conversion.cc_id = fundraising_course.fc_currency", 'left')
            ->where('cc_toid', "{$currency}")
            ->get()->result();
    }

    public function getFundraisingCourseDataPoint($fc_id = ''){
        return ($this->db->select("FORMAT((fc_fundraisingPrice * cc_exchangeRate * 3), 2) AS point")
            ->from('fundraising_course')
            ->where('fc_id', $fc_id)
            ->join('currency_conversion', "currency_conversion.cc_id = fundraising_course.fc_currency")
            ->where('cc_toid', 'TWD')
            ->get()->row())->point;
    }

    public function checkRelease($id = ''){
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $id)->where('fc_status', 1)->get()->num_rows();
    }

    public function checkInterest($id = ''){
        return $this->db->select('*')->from('fundraising_course_list')->where('fc_id', $id)->where('m_id', $_SESSION['Mid'])->get()->num_rows();
    }

    public function fundraisingCourseInterested($insert = ''){
        return $this->db->insert('fundraising_course_list', $insert);
    }

    public function reduceFundraisingCoursePeople($fc_id = '')
    {
        return $this->db->query("UPDATE fundraising_course SET fc_remainingNumber = fc_remainingNumber - 1 WHERE fc_id = '{$fc_id}'");
    }

    public function lessPoint($fc_id = '', $point){
        $data = $this->db->select('points')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row();
        if($data == null)
            return true;
        if($data->points == 0 | $data->points == null)
            return true;

        if($data->points < $point)
            return true;
        if($this->db->query("UPDATE main SET points = points - $point WHERE m_id = '{$_SESSION['Mid']}'"))
            return false;
        else
            return true;
    }

    public function checkMessageIsMy($fc_id = ''){
        if(!isset($_SESSION['Tid']))
            return 0;
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function getMessageData($status = 0){
        if($status) {
            $returnData = $this->db->select('m_photo, t_name AS name')->from('main')->where('main.t_id', $_SESSION['Tid'])
                ->join('teacher', 'teacher.t_id = main.t_id', 'left')
                ->join('member', 'member.m_id = main.m_id', 'left')
                ->get()->row();
            $returnData->identity = '老師';
        }else{
            $returnData = $this->db->select('m_photo, m_name AS name')->from('member')->where('m_id', $_SESSION['Mid'])->get()->row();
            $returnData->identity = '學員';
        }
        return $returnData;
    }

    public function getMessage($fc_id = '', $index = ''){
        return $this->db->select('fcm_id AS messageID, fcm_message AS message, fcm_date AS date, fcm_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
         IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(fundraisingCourse_message.t_id, "老師", "學員") AS identity')
            ->from('fundraisingCourse_message')
            ->where('fc_id', $fc_id)
            ->join('teacher', 'fundraisingCourse_message.t_id = teacher.t_id', 'left')
            ->join('member', 'fundraisingCourse_message.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('fcm_date DESC')
            ->get()->result();
    }
    public function getMessageReply($fcm_id = '', $index = ''){
        return $this->db->select('fcmr_message AS message, fcmr_date AS date, fcmr_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
        IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(fundraisingCourse_message_reply.t_id, "老師", "學員") AS identity')
            ->from('fundraisingCourse_message_reply')
            ->where('fcm_id', $fcm_id)
            ->join('teacher', 'fundraisingCourse_message_reply.t_id = teacher.t_id', 'left')
            ->join('member', 'fundraisingCourse_message_reply.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('fcmr_date DESC')
            ->get()->result();
    }

    public function getCourseFavorite($fc_id = ''){
        if($this->db->select('*')->from('course_favorite')->where('cf_id', $fc_id)->where('m_id', $_SESSION['Mid'])->get()->num_rows() == 1)
            return true;
        else
            return false;
    }
}