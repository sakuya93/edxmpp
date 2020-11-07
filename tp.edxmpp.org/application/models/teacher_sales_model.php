<?php

class teacher_sales_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getCourses_data($kind = "", $course_id = "")
    {
            return $this->db->select('teacherStatus, l_id as id, l_experienceFilm as experienceFilm, l_type as type, l_thumbnail as thumbnail, l_actualMovie as actualMovie,
             l_introduction as introduction, l_hours as hours, l_price as price, l_numberPeople as numberPeople, l_evaluation as evaluation,
              l_briefIntroduction AS brief_introduction, t_name as name,t_speakLanguage as speakLanguage,t_des as des, t_photo as photo, live.t_id AS id2')
                ->from('live')
                ->where('l_id', $course_id)
                ->join('teacher', 'live.t_id = teacher.t_id', 'left')
                ->join('main', 'live.t_id = main.t_id', 'left')
                ->get()->result();
    }

    public function getNumberLessonsPreferential($id = '', $currency = 'TWD'){
        return $this->db->select('cd_number as number, (cd_discountedPrices * cc_exchangeRate) as discountedPrice')
            ->from('courtdiscount')
            ->where('l_id', $id)
            ->order_by('cd_number', "asc")
            ->join('currency_conversion', "currency_conversion.cc_id = courtdiscount.cd_currency")
            ->where('cc_toid', "{$currency}")
            ->get()->result();
    }

    public function getMatchTime($id = '')
    {
        return $this->db->select('lt_maxPeople, lt_time, lt_id, lt_note')->from('livetime')->where('l_id', $id)->get()->result();
    }
    public function getContactWindow($t_id = ''){
        return $this->db->select('cw_id AS id, cw_Tid AS id2, id AS id3')->from('contact_window')->where('cw_TID', $t_id)->where('cw_MID', $_SESSION['Mid'])->order_by('cw_date', 'DESC')->get()->row();
    }

    public function checkLive($l_id = ''){
        return $this->db->select('*')->from('live')->where('l_id', $l_id)->get()->num_rows();
    }

    public function getMessage($l_id = '', $index = ''){
        return $this->db->select('lm_id AS messageID, lm_message AS message, lm_date AS date, lm_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
         IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(live_message.t_id, "老師", "學員") AS identity')
            ->from('live_message')
            ->where('l_id', $l_id)
            ->join('teacher', 'live_message.t_id = teacher.t_id', 'left')
            ->join('member', 'live_message.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('lm_date DESC')
            ->get()->result();
    }

    public function getMessageReply($lm_id = '', $index = ''){
        return $this->db->select('lmr_message AS message, lmr_date AS date, lmr_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
        IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(live_message_reply.t_id, "老師", "學員") AS identity')
            ->from('live_message_reply')
            ->where('lm_id', $lm_id)
            ->join('teacher', 'live_message_reply.t_id = teacher.t_id', 'left')
            ->join('member', 'live_message_reply.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('lmr_date DESC')
            ->get()->result();
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
            $returnData->identity = '學生';
        }
        return $returnData;
    }

    public function checkMessageIsMy($l_id = ''){
        if(!isset($_SESSION['Tid']))
            return 0;
        return $this->db->select('*')->from('live')->where('l_id', $l_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

     public function getCourseTid($l_id = ''){
        return ($this->db->select('t_id')->from('live')->where('l_id', $l_id)
            ->join('teacher', 'teacher.t_id = live.t_id', 'left'))->t_id;
     }

     public function checkMemberIsNull($m_id = ''){
        return $this->db->select('*')->from('member')->where('m_id', $m_id)->get()->num_rows();
     }

     public function checkDuplicateApplication($l_id = ''){
        return $this->db->select('*')->from('experience_class')->where('l_id', $l_id)->where('m_id', $_SESSION['Mid'])->get()->num_rows();
     }

     public function addExperienceClass($insert = ''){
        return $this->db->insert('experience_class', $insert);
     }

     public function checkFixedClassIfBuy($l_id = ''){
        return $this->db->select('*')->from('shoppingCart')->where('l_id', $l_id)->where('m_id', $_SESSION['Mid'])->get()->num_rows();
     }

     public function getCourseFavorite($l_id = ''){
        if($this->db->select('*')->from('course_favorite')->where('cf_id', $l_id)->where('m_id', @$_SESSION['Mid'])->get()->num_rows() == 1)
            return true;
        else
            return false;
     }
}
