<?php

class admin_Teams_liveManagement_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getLiveMatchTime($day = ''){
        return $this->db->select('liveTime.lt_id AS id, lt_time AS matchTime, (lt_maxPeople - lt_lastPeople) AS matchPeople, t_name AS teacherName, l_actualMovie AS liveName,(select @rownum:=0)')
            ->from('liveTime')
            ->like('lt_time', "{$day}_")
            ->where('lt_type', null)
            ->join('live', 'liveTime.l_id = live.l_id', 'inner')
            ->join('teacher', 'liveTime.t_id = teacher.t_id', 'inner')
            ->get()->result();
    }

    public function getLiveMatchTimeDetail($id = ''){
        $returnData['matchData'] = $this->db->select('liveTime.lt_id AS id, lt_time AS matchTime, (lt_maxPeople - lt_lastPeople) AS matchPeople, lt_note AS note, t_name AS teacherName, l_actualMovie AS liveName')
            ->from('liveTime')
            ->where('liveTime.lt_id', $id)
            ->join('live', 'liveTime.l_id = live.l_id', 'left')
            ->join('teacher', 'liveTime.t_id = teacher.t_id', 'left')
            ->get()->row();
        $returnData['student'] = $this->db->select('m_teamsAccount AS teamsAccount')
            ->from('liveMatchTime')
            ->where('lt_id', $id)
            ->join('member', 'liveMatchTime.m_id = member.m_id', 'left')
            ->get()->result();
        return $returnData;
    }

    public function getTeamsLiveData($id = ''){
        $returnData['matchData'] = $this->db->select('liveTime.lt_id AS id, lt_time AS matchTime, (lt_maxPeople - lt_lastPeople) AS matchPeople, lt_note AS note, t_name AS teacherName, l_actualMovie AS liveName')
            ->from('liveTime')
            ->where('liveTime.lt_id', $id)
            ->join('live', 'liveTime.l_id = live.l_id', 'left')
            ->join('teacher', 'liveTime.t_id = teacher.t_id', 'left')
            ->get()->row();
        $returnData['studentTeamsAccount'] = $this->db->select('m_teamsAccount AS teamsAccount')
            ->from('liveMatchTime')
            ->where('lt_id', $id)
            ->join('member', 'liveMatchTime.m_id = member.m_id', 'left')
            ->get()->result();
        //array_push($returnData['teamsAccount'], $this->db->select('teamsAccount')->from('admin')->where('account', $_SESSION['admin_name'])->get()->row());
        $returnData['teacherTeamsAccount'] = $this->db->select('t_teamsAccount AS teamsAccount')
            ->from('liveTime')
            ->where('liveTime.lt_id', $id)
            ->join('teacher' , 'liveTime.t_id = teacher.t_id', 'inner')
            ->get()->row();
        //array_push($returnData['teamsAccount'], $data);
        return $returnData;
    }

    public function completeMeetingLayout($id = ''){
        return $this->db->where('lt_id', $id)->update('liveTime', array('lt_type' => '1'));
    }
}