<?php

class course_introduction_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getCourses_data($kind = "", $page = 1, $type = '', $currency = 'TWD')
    {
        if ($kind == 'live') {

            return $this->db->select('teacherStatus, live.l_id as id, l_experienceFilm as experienceFilm, l_type as type, l_thumbnail as photo, l_actualMovie as actualMovie,
             l_briefIntroduction as briefIntroduction, l_hours as hours, (cd_discountedPrices * cc_exchangeRate) as price, t_name as name, cd_currency AS currency,
              FORMAT(l_evaluation, 1) AS evaluation')
                ->from('live')
                ->join('main', 'live.t_id = main.t_id', 'left')
                ->where('teacherStatus', 1)
                ->where('l_release', 1)
                ->group_start()
                ->or_like('l_type', $type)
                ->or_like('l_experienceFilm', $type)
                ->or_like('l_actualMovie', $type)
                ->or_like('l_introduction', $type)
                ->or_like('l_label', $type)
                ->or_like('t_name', $type)
                ->group_end()
                ->join('teacher', 'main.t_id = teacher.t_id ', 'left')
                ->join('courtdiscount', 'live.l_id = courtdiscount.l_id', 'inner')
                ->join('currency_conversion', "currency_conversion.cc_id = courtdiscount.cd_currency")
                ->where('cc_toid', "{$currency}")
                ->group_by('courtdiscount.l_id')
                ->limit(5, ($page - 1) * 5)
                ->get()->result();
        }
        else if ($kind == 'film')
            return $this->db->select('cf_id as id, cf_experienceFilm as experienceFilm, cf_type as type, cf_thumbnail as photo, cf_name as actualMovie,
             cf_briefIntroduction as briefIntroduction, cf_hours as hours, (cf_price * cc_exchangeRate) as price, t_name as name, FORMAT(cf_evaluation, 1) AS evaluation')
                ->from('coursefilm')
                ->join('main', 'coursefilm.t_id = main.t_id', 'left')
                ->where('teacherStatus', 1)
                ->where('cf_release', 1)
                ->where('cf_experienceFilmName !=', null)
                ->where('cf_actualMovie', null)
                ->group_start()
                ->like('cf_type', $type)
                ->or_like('cf_experienceFilm', $type)
                ->or_like('cf_name', $type)
                ->or_like('cf_introduction', $type)
                ->or_like('cf_label', $type)
                ->or_like('t_name', $type)
                ->group_end()
                ->join('teacher', 'teacher.t_id = coursefilm.t_id', 'left')
                ->join('currency_conversion', "currency_conversion.cc_id = cf_currency")
                ->where('cc_toid', "{$currency}")
                ->limit(5, ($page - 1) * 5)
                ->get()->result();
        else if ($kind == 'fundraisingCourse')
            return $this->db->select('fc_id AS id, fc_courseName AS experienceFilm, fc_type AS type, fc_image AS photo, fc_courseName AS actualMovie, fc_briefIntroduction AS briefIntroduction, fc_hours AS hours
            ,(fc_fundraisingPrice * cc_exchangeRate) AS price, t_name AS name')
                ->from('fundraising_course')
                ->join('main', 'fundraising_course.t_id = main.t_id', 'left')
                ->where('teacherStatus', 1)
                ->where('fc_status', 1)
                ->group_start()
                ->like('fc_courseName', $type)
                ->or_like('fc_type', $type)
                ->or_like('fc_courseIntroduction', $type)
                ->or_like('fc_label', $type)
                ->or_like('t_name', $type)
                ->group_end()
                ->join('teacher', 'teacher.t_id = fundraising_course.t_id', 'left')
                ->join('currency_conversion', "currency_conversion.cc_id = fc_currency")
                ->where('cc_toid', "{$currency}")
                ->where('fc_endTime >', date("Y-m-d H:i:s"))
                ->limit(5, ($page - 1) * 5)
                ->get()->result();
    }

    public function getFavorite($data = ''){
        $count = count($data);
        if($count == 1)
            return $this->db->select('cf_id')
                ->from('course_favorite')
                ->where('cf_id', $data[0]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->get()->result();
        elseif($count == 2)
            return $this->db->select('cf_id')
                ->from('course_favorite')
                ->group_start()
                ->where('cf_id', $data[0]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[1]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->get()->result();
        elseif($count == 3)
            return $this->db->select('cf_id')
                ->from('course_favorite')
                ->group_start()
                ->where('cf_id', $data[0]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[1]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[2]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->get()->result();
        elseif($count == 4)
            return $this->db->select('cf_id')
                ->from('course_favorite')
                ->group_start()
                ->where('cf_id', $data[0]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[1]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[2]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[3]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->get()->result();
        elseif($count == 5)
            return $this->db->select('cf_id')
                ->from('course_favorite')
                ->group_start()
                ->where('cf_id', $data[0]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[1]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[2]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[3]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->or_group_start()
                ->where('cf_id', $data[4]->id)
                ->where('m_id', @$_SESSION['Mid'])
                ->group_end()
                ->get()->result();
    }

    public function countData($kind = "", $type = "")
    {
        if (isset($_SESSION['Tid'])) {
            if ($kind == 'live')
                return $this->db->select('*')
                    ->from('live')
                    ->join('main', 'live.t_id = main.t_id', 'inner')
                    ->where('main.teacherStatus', 1)
                    ->where('l_release', 1)
                    ->group_start()
                    ->like('l_type', $type)
                    ->or_like('l_experienceFilm', $type)
                    ->or_like('l_actualMovie', $type)
                    ->or_like('l_introduction', $type)
                    ->or_like('l_label', $type)
                    ->group_end()
                    ->join('courtdiscount', 'live.l_id = courtdiscount.l_id', 'inner')
                    ->group_by('courtdiscount.l_id')
                    ->get()->num_rows();
            else
                return $this->db->select('*')
                    ->from('coursefilm')
                    ->join('main', 'coursefilm.t_id = main.t_id', 'left')
                    ->where('teacherStatus', 1)
                    ->where('cf_release', 1)
                    ->where('cf_experienceFilmName !=', null)
                    ->where('cf_actualMovie', null)
                    ->group_start()
                    ->like('cf_type', $type)
                    ->or_like('cf_experienceFilm', $type)
                    ->or_like('cf_name', $type)
                    ->or_like('cf_introduction', $type)
                    ->or_like('cf_label', $type)
                    ->group_end()
                    ->get()->num_rows();
        } else
            return null;
    }

    public function getClassOptionKey($tempKey = '')
    {
        return $this->db->select('option')->from('classOption')->where('key_words', $tempKey)->get()->row();
    }
}
