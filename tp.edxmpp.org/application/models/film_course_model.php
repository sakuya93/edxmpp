<?php

class film_course_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
    public function checkFilmIsNull($cf_id = ''){
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $cf_id)->get()->num_rows();
    }

    public function check_coursesBasicInformation($data){
        if($this->db->select('*')->from('courseFilm')->where('cf_actualMovie', $data)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function add_coursesBasicInformation($dataArray){
        return $this->db->insert('courseFilm', $dataArray);
    }

    public function check_actualMovie($dataArray){
        $where = array();
        foreach($dataArray as $temp)
            $where[] = $temp['cf_actualMovieName'];
        if($this->db->select('*')->from('courseFilm')->where_in('cf_actualMovieName', $where)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function add_actualMovie($dataArray){
        return $this->db->insert_batch('courseFilm', $dataArray);
    }

    public function getFilm($id = '', $currency = ''){
        return $this->db->select('courseFilm.t_id as id1, cf_id as id2, cf_experienceFilmName as experienceFilmName,
        cf_type as type, cf_actualMovieName as actualMovieName, cf_name as name, cf_briefIntroduction AS brief_introduction,
        cf_introduction as introduction, cf_hours as hours, cf_evaluation as evaluation,
        teacherStatus AS teacher_status, t_name AS teacherName, t_speakLanguage AS speakLanguage, t_country AS country, t_photo AS image,
        t_age AS age, t_sex AS sex, teacher.t_id AS id3, cf_unitName AS unitName')
            ->from('courseFilm')
            ->where('cf_id', $id)
            ->join('main', 'main.t_id = courseFilm.t_id', 'left')
            ->join('teacher', 'main.t_id = teacher.t_id', 'left')
            ->get()->result();
    }

    public function getFilmPrice($id = '', $currency = ''){
         $result = $this->db->select('FORMAT(cf_price * cc_exchangeRate, 2) as price')
            ->from('courseFilm')
            ->where('cf_id', $id)
            ->join('main', 'main.t_id = courseFilm.t_id', 'left')
            ->join('teacher', 'main.t_id = teacher.t_id', 'left')
            ->join('currency_conversion', 'currency_conversion.cc_id = cf_currency', 'inner')
            ->where('cc_toid', $currency)
            ->get()->result();

         return $result[0]->price;
    }

    public function getCourseFavorite($cf_id = ''){
        if($this->db->select('*')->from('course_favorite')->where('cf_id', $cf_id)->where('m_id', @$_SESSION['Mid'])->get()->num_rows() == 1)
            return true;
        else
            return false;
    }

    public function checkFilmBuy($id = ''){
        if($this->db->select('*')->from('courseFilm')->where('t_id', @$_SESSION['Tid'])->where('cf_id', $id)->get()->num_rows())
            return 2;
        return $this->db->select('*')->from('shoppingCart')->where('m_id', $_SESSION["Mid"])->where('cf_id', $id)->where('sc_payStatus', 1)->get()->num_rows();
    }

    public function getVideoUrl($id = '', $index = 0){
        if($index == 0)
            return $this->db->select('cf_experienceFilm as url')->from('courseFilm')->where('cf_id', $id)->where('cf_experienceFilm !=', null)->get()->row();
        else
            return $this->db->select('cf_actualMovie as url')->from('courseFilm')->where('cf_id', $id)->where('cf_actualMovieIndex', $index)->get()->row();
    }

    public function checkRelease($id = ''){
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $id)->where('cf_release', 0)->get()->num_rows();
    }

    public function getVideoWatchHistory($id = '', $index =''){
        return $this->db->select('vwh_date AS date')->from('video_watch_history')->where('cf_id', $id)->where('m_id', $_SESSION['Mid'])->where('cf_actualMovieIndex', $index)->get()->row();
    }

    public function addVideoWatchHistory($data = '', $bo = ''){
        if($bo){
            return $this->db->insert('video_watch_history', $data);
        }else{
            return $this->db->where('cf_id', $data['cf_id'])->where('m_id', $data['m_id'])->where('cf_actualMovieIndex', $data['cf_actualMovieIndex'])->update('video_watch_history', $data);
        }
    }

    public function getContactWindow($t_id = ''){
        return $this->db->select('cw_id AS id, cw_Tid AS id2, id AS id3')->from('contact_window')->where('cw_TID', $t_id)->where('cw_MID', $_SESSION['Mid'])->order_by('cw_date', 'DESC')->get()->row();
    }

    public function checkFilm($cf_id = ''){
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $cf_id)->get()->num_rows();
    }

    public function getMessage($cf_id = '', $index = ''){
        return $this->db->select('fm_id AS messageID, fm_message AS message, fm_date AS date, fm_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
         IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(film_message.t_id, "老師", "學員") AS identity')
            ->from('film_message')
            ->where('cf_id', $cf_id)
            ->join('teacher', 'film_message.t_id = teacher.t_id', 'left')
            ->join('member', 'film_message.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('fm_date DESC')
            ->get()->result();
    }
    public function getMessageReply($fm_id = '', $index = ''){
        return $this->db->select('fmr_message AS message, fmr_date AS date, fmr_adminStatus AS admin_message, member.m_id AS memberID, teacher.t_id AS teacherID,
        IFNULL(t_photo, m_photo) AS photo, IFNULL(t_name, m_name) AS name, IF(film_message_reply.t_id, "老師", "學員") AS identity')
            ->from('film_message_reply')
            ->where('fm_id', $fm_id)
            ->join('teacher', 'film_message_reply.t_id = teacher.t_id', 'left')
            ->join('member', 'film_message_reply.m_id = member.m_id', 'left')
            ->limit(5, ($index * 5) )
            ->order_by('fmr_date DESC')
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
            $returnData->identity = '學員';
        }
        return $returnData;
    }

    public function checkMessageIsMy($l_id = ''){
        if(!isset($_SESSION['Tid']))
            return 0;
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $l_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }
}