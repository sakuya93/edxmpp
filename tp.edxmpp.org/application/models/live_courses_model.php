<?php

class live_courses_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function check_courses($data)
    {
        if ($this->db->select('*')->from('live')->where('l_actualMovie', $data)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function add_courses($dataArray)
    {
        return $this->db->insert('live', $dataArray);
    }

    public function delete_courses($data)
    {
        //刪除圖片
        $image = $this->db->select('l_thumbnail')->from('live')->where('l_actualMovie', $data)->get()->row();
        $image = $image->l_thumbnail;
        $image_path = 'resource/image/teacher/live/';
        if (file_exists("{$image_path}{$image}")) {
            unlink("{$image_path}{$image}");
            //刪除這筆資料
            return $this->db->delete('live', array('l_actualMovie' => $data));
        }
    }


    public function search_courses($data)
    {
        if (isset($_SESSION['Tid'])) {
            return $this->db->select('l_id as id, l_experienceFilm as experienceFilm, l_type as type, l_thumbnail as thumbnail, l_actualMovie as actualMovie, l_introduction as introduction, l_hours as hours, l_price as price, l_numberPeople as numberPeople, l_evaluation as evaluation, l_briefIntroduction AS briefIntroduction')
                ->from('live')
                ->where('l_id', $data)
                ->get()->row();
        } else
            return null;
    }

    public function check_courses_name($id, $name)
    {
        $data = $this->db->select('l_id as id, l_actualMovie as actualMovie')
            ->from('live')
            ->where('l_id != ', $id)
            ->where('l_actualMovie', $name)
            ->get()->row();
        if ($data == null) {
            return true;
        } else {
            return false;
        }
    }

    public function update_courses($id, $data)
    {
        if ($this->db->where('l_id', $id)->update('live', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkCourses($id = '')
    {
        $data = $this->db->select('t_id')->from('live')->where('l_id', $id)->get()->row();
        if ($_SESSION['Tid'] == $data->t_id)
            return true;
        else
            return false;
    }

    public function getOption()
    {
        return $this->db->select('option')->from('classOption')->get()->result();
    }

    public function getClassName($l_id = '')
    {
        return ($this->db->select('l_actualMovie AS actualMovie')->from('live')->where('l_id', $l_id)->get()->row())->actualMovie;
    }

    public function getClassMode($l_id = ''){
        return ($this->db->select('l_classMode')->from('live')->where('l_id', $l_id)->get()->row())->l_classMode;
    }

    public function getClassMode2($lt_id = ''){
        return ($this->db->select('l_classMode')->from('liveTime')->where('liveTime.lt_id', $lt_id)->join('live', 'live.l_id = liveTime.l_id', 'inner')
            ->get()->row())->l_classMode;
    }

    public function getTeacherTeamsApiKey(){
        return $this->db->select('application_key AS applicationKey, list_key AS listKey')->from('teacher')->where('t_id', $_SESSION['Tid'])->get()->row();
    }

    public function getClassList($lt_id = '', $classMode = 0, $l_id = '')
    {
        if($classMode == '1')
            return $this->db->select('m_teamsAccount AS teamsAccount, m_name AS studentName')
                ->from('shoppingCart')->where('l_id', $l_id)->where('sc_payStatus', '1')
                ->join('member', 'member.m_id = shoppingCart.m_id', 'left')->get()->result();
        else
            return $this->db->select('m_teamsAccount AS teamsAccount, m_name AS studentName')
                ->from('liveMatchTime')->where('lt_id', $lt_id)->join('member', 'liveMatchTime.m_id = member.m_id', 'left')->get()->result();
    }

    public function getClassList2($lt_id = '', $classMode = 0){
        if($classMode == '1')
            return $this->db->select('m_teamsAccount AS teamsAccount, m_name AS studentName')
                ->from('liveTime')->where('lt_id', $lt_id)
                ->join('shoppingCart', 'shoppingCart.l_id = liveTime.l_id', 'inner')
                ->where('sc_payStatus', '1')
                ->join('member', 'member.m_id = shoppingCart.m_id', 'left')->get()->result();
        else
            return $this->db->select('m_teamsAccount AS teamsAccount, m_name AS studentName')
                ->from('liveMatchTime')->where('lt_id', $lt_id)->join('member', 'liveMatchTime.m_id = member.m_id', 'left')->get()->result();
    }

    public function getMatchTime($lt_id = '')
    {
        return ($this->db->select('lt_time')->from('liveTime')->where('lt_id', $lt_id)->get()->row())->lt_time;
    }

    public function checkAuthority($lt_id = '')
    {
        return $this->db->select('*')->from('liveTime')->where('lt_id', $lt_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function checkTeamsCreateStatus($lt_id = '')
    {
        return $this->db->select('*')->from('liveTime')->where('lt_id', $lt_id)->where('lt_teamsCreateStatus', '1')->get()->num_rows();
    }

    public function updateTeamsCreateStatus($data = '')
    {
        return $this->db->where('lt_id', $data['id'])->where('t_id', $_SESSION['Tid'])->update('liveTime', array('lt_note' => $data['note'], 'lt_teamsCreateStatus' => '1'));
    }

    public function getClassData($data = '')
    {
        return $this->db->select('t_name, l_actualMovie, lt_time, lt_note')->from('liveTime')->where('liveTime.lt_id', $data['id'])
            ->join('teacher', 'teacher.t_id = liveTime.t_id', 'inner')
            ->join('live', 'live.l_id = liveTime.l_id', 'inner')
            ->get()->row();
    }

    public function checkNumberStudents($lt_id = ''){
        return $this->db->select('*')->from('liveMatchTime')->where('lt_id', $lt_id)->get()->num_rows();
    }

    public function getExperienceClass(){
        return $this->db->select('m_name AS memberName, m_photo AS memberPhoto, l_actualMovie AS courseName, experience_class.m_id AS memberID,
        experience_class.l_id AS courseID, cw_id As contactID')
            ->from('experience_class')
            ->where('experience_class.t_id', $_SESSION['Tid'])
            ->join('live', 'live.l_id = experience_class.l_id', 'left')
            ->join('member', 'member.m_id = experience_class.m_id', 'left')
            ->join('contact_window', 'contact_window.cw_TID = experience_class.t_id AND contact_window.cw_MID = experience_class.m_id', 'inner')
            ->group_by(array('cw_TID', 'experience_class.l_id', 'experience_class.m_id'))
            ->get()->result();
    }

    public function checkLabelIsNull($label = ''){
        return $this->db->select('*')->from('course_label')->where('label', $label)->get()->num_rows();
    }
}