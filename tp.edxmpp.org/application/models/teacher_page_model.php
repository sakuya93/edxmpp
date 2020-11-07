<?php

class teacher_page_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkTeacherIsNull($t_id = ''){
        return $this->db->select('*')->from('teacher')->where('t_id', $t_id)->get()->num_rows();
    }

    public function getTeacherData($id = '')
    {
        return $this->db->select('t_name AS name, t_country AS country, t_speakLanguage AS speakLanguage, email_date AS registeredData, m_date AS date, m_timezone AS timeZone, t_photo AS photo')
            ->from('main')
            ->where('main.t_id', $id)
            ->join('teacher', 'main.t_id = teacher.t_id', ' left')
            ->join('member', 'main.m_id = member.m_id', 'left')
            ->get()->row();
    }

    public function getLiveData($id = '')
    {
        return $this->db->select('l_type AS type, l_hours AS hours, l_id AS id, l_thumbnail AS photo, l_actualMovie AS name, l_evaluation AS score')
            ->from('live')
            ->where('t_id', $id)
            ->get()->result();
    }

    public function getFilmData($id = '')
    {
        return $this->db->select('cf_type AS type, cf_id AS id, cf_thumbnail AS photo, cf_name AS name, cf_price AS price')
            ->from('coursefilm')
            ->where('t_id', $id)
            ->where('cf_actualMovie', null)
            ->get()->result();
    }

    public function getCourseEvaluation($id = '')
    {
        $returnData = new stdClass();
        $returnData->film = $this->db->select('coursefilm.cf_id AS id, cf_name AS name, ce_level AS score, ce_comment AS comment')
            ->from('coursefilm')
            ->where('coursefilm.t_id', $id)
            ->where('ce_comment !=', null)
            ->join('courseevaluation', 'coursefilm.cf_id = courseevaluation.cf_id', 'left')
            ->group_by('courseevaluation.cf_id')
            ->limit(5)->get()->result();
        $returnData->live = $this->db->select('live.l_id AS id, l_actualMovie AS name, ce_level AS score, ce_comment AS comment')
            ->from('live')
            ->where('live.t_id', $id)
            ->where('ce_comment !=', null)
            ->join('courseevaluation', 'live.l_id = courseevaluation.l_id', 'left')
            ->group_by('courseevaluation.l_id')
            ->limit(5)->get()->result();
        return $returnData;
    }
}