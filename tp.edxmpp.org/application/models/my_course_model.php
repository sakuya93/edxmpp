<?php

class my_course_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getMyCourseCount()
    {
        return $this->db->select('*')->from('shoppingcart')
            ->where('m_id', $_SESSION['Mid'])
            ->where('sc_payStatus', '1')
            ->where('sc_NumberOfLessons !=', 0)
            ->get()->num_rows();
    }

    public function getMyCourse($page = 1)
    {
        $result = $this->db
            ->distinct()
            ->select('shoppingcart.*, teacher.t_name, teacher.t_country as country, teacher.t_speakLanguage as speakLanguage, teacher.t_photo as photo,
             live.l_hours as hours, live.l_price as price, l_type as type, l_thumbnail, cf_thumbnail')
            ->from('shoppingcart')
            ->where('shoppingcart.m_id', $_SESSION['Mid'])
            ->where('sc_payStatus', '1')
            ->join('teacher', 'shoppingcart.t_id = teacher.t_id', 'left')
            ->join('live', 'shoppingcart.l_id = live.l_id', 'left')
            ->join('coursefilm', 'shoppingcart.cf_id = coursefilm.cf_id', 'left')
            ->order_by('sc_date', 'DESC')
            ->limit(10, ($page - 1) * 10)
            ->get()->result();

        for ($i = 0; $i < count($result); $i++) { //抓取出錯誤資料並刪除
            if($result[$i]->l_thumbnail == NULL & $result[$i]->cf_thumbnail == NULL){
                unset($result[$i]);
            }
        }
        $result = array_values($result); //因有刪除元素所以這邊重新排列陣列

        return $result;
    }

    public function getMatchTime($id = '')
    {
        return $this->db->select('lt_lastPeople, lt_time, liveTime.lt_id as lt_id, lt_note AS note')->from('liveTime')->where('l_id', $id)->get()->result();
    }

    public function getWhetherMatchTime($lt_id = '')
    {
        return $this->db->select('*')->from('liveMatchTime')->where('lt_id', $lt_id)->where('m_id', $_SESSION['Mid'])->get()->num_rows();
    }

    public function getMatchTimeOne($lt_id = '')
    {
        return ($this->db->select('lt_time')->from('liveTime')->where('lt_id', $lt_id)->get()->row())->lt_time;
    }

    public function addStudentMatchTime($insert = '', $sc_id = '')
    {
        $this->db->query("UPDATE shoppingcart SET sc_NumberOfLessons = sc_NumberOfLessons - 1 WHERE sc_id = '{$sc_id}'");
        $this->db->query("UPDATE liveTime SET lt_lastPeople = lt_lastPeople - 1 WHERE lt_id = '{$insert['lt_id']}'");
        return $this->db->insert('liveMatchTime', $insert);
    }

    public function checkClassLastPeople($id = '')
    {
        return $this->db->select('*')->from('liveTime')->where('lt_id', $id)->where('lt_lastPeople =', '0')->get()->num_rows();
    }

    public function checkStudentMatchTime($dataArray = null)
    {
        return $this->db->select('*')->from('liveMatchTime')->where('m_id', $_SESSION['Mid'])->where('lt_id', $dataArray['lt_id'])->get()->num_rows();
    }

    public function checkClassLastHours($id = '')
    {
        return $this->db->select('*')->from('shoppingcart')->where('sc_id', $id)->where('sc_NumberOfLessons >', 0)->get()->num_rows();
    }

    public function getFilmData($id = '')
    {
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $id)->where('cf_experienceFilm !=', null)->get()->row();
    }

    public function deleteStudentMatchTime($insert = '', $sc_id = '')
    {
        $data = $this->db->select('lt_lastPeople')->from('liveTime')->where('lt_id', $insert['lt_id'])->get()->row();
        $nol = $this->db->select('sc_NumberOfLessons as nol')->from('shoppingcart')->where('sc_id', $sc_id)->get()->row();
        $this->db->where('sc_id', $sc_id)->update('shoppingcart', array('sc_NumberOfLessons' => ++$nol->nol));
        $this->db->where('lt_id', $insert['lt_id'])->update('liveTime', array('lt_lastPeople' => ++$data->lt_lastPeople));
        $bo = $this->db->delete('liveMatchTime', array('lt_id' => $insert['lt_id']));
        return json_encode(array('status' => $bo, 'lastPeople' => $data->lt_lastPeople));
    }
}
