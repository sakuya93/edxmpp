<?php

class my_course_evaluation_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getCourse_st_data($course = ''){ //取得該課程的學生資料
        return $this->db
            ->select('member.m_name , member.m_email, member.m_country, member.m_speakLanguage, member.m_city, member.m_photo, member.m_line, 
            shoppingCart.l_id, shoppingCart.sc_className, shoppingCart.cf_id, live.l_type as type, ce_comment as comment, ce_level as score')
            ->from('member')
            ->where('shoppingCart.t_id', $_SESSION['Tid'])
            ->where('shoppingCart.sc_className', $course)
            ->where('shoppingCart.sc_payStatus', 1)
            ->join('shoppingCart', 'shoppingCart.m_id = member.m_id', 'left')
            ->join('live', 'live.l_id = shoppingCart.l_id', 'left')
            ->join('courseFilm', 'shoppingCart.cf_id = courseFilm.cf_id', 'left')
            ->join('courseEvaluation', 'shoppingCart.sc_id = courseEvaluation.sc_id', 'left')
            ->get()->result();
    }

    public function getFilmData($id =''){
        return $this->db->select('cf_type')->from('courseFilm')->where('cf_id', $id)->where('cf_experienceFilm !=', null)->get()->result();
    }

    public function getAllCourseOption($t_id = ''){ //取得課程選項按鈕資訊
        //直播課程
        $result['live'] = $this->db->select("l_actualMovie")
            ->from('live')
            ->where('t_id', $t_id)
            ->get()->result_array();
        
        //影片課程
        $result['courseFilm'] = $this->db->select("cf_name")
            ->from('courseFilm')
            ->where('t_id', $t_id)
            ->where('cf_actualMovie is Null')
            ->get()->result_array();
        return $result;
    }
}