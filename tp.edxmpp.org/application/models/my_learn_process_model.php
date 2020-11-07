<?php

class my_learn_process_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkComment($data = '')
    {
        if ($this->db->select('*')->from('courseEvaluation')->where('sc_id', $data['sc_id'])->where('m_id', $_SESSION['Mid'])->get()->num_rows() != 0)
            return true;
        else
            return false;
    }

    public function addComment($data = '')
    {
        if ($this->db->insert('courseEvaluation', $data)) {
            $evaluation = $this->getEvaluation($data['l_id'], 'live');
            $this->db->where('l_id', $data['l_id'])->update('live', array('l_evaluation' => $evaluation));
            return true;
        }
        return false;
    }

    public function addCommentFilm($data = ''){
        if ($this->db->insert('courseEvaluation', $data)) {
            $evaluation = $this->getEvaluation($data['cf_id'], 'film');
            $this->db->where('cf_id', $data['cf_id'])->update('courseFilm', array('cf_evaluation' => $evaluation));
            return true;
        }
        return false;
    }

    public function deleteComment($id = '', $id2 = '')
    {
        if ($this->db->delete('courseEvaluation', array('sc_id' => $id, 'm_id' => $_SESSION['Mid']))) {
            $evaluation = $this->getEvaluation($id2);
            $this->db->where('l_id', $id2)->update('live', array('l_evaluation' => $evaluation));
            return true;
        }
        return false;
    }

    public function getEvaluation($id = '', $courseType = 'live')
    {
        if($courseType == 'live') {
            $evaluation = $this->db
                ->select('count(l_id) as count, sum(ce_level) as score')
                ->from('courseEvaluation')
                ->where('l_id', $id)
                ->get()->row();
            if ($evaluation->count == 0)
                return 0;
            elseif ($evaluation->count == 1)
                return $evaluation->score;
            return $evaluation->score / $evaluation->count;
        }else{
            $evaluation = $this->db
                ->select('count(cf_id) as count, sum(ce_level) as score')
                ->from('courseEvaluation')
                ->where('cf_id', $id)
                ->get()->row();
            if ($evaluation->count == 0)
                return 0;
            elseif ($evaluation->count == 1)
                return $evaluation->score;
            return $evaluation->score / $evaluation->count;
        }
    }

    public function getComment($getType = 'type_live_course')
    {
        if ($getType == 'type_live_course')
            return $this->db->select('l.*, ce.ce_id as courseEvaluationID, ce_comment as comment, ce_level as score, sc.m_id as studentID, t_name as teacherName, sc.sc_id as shoppingCartID')
                ->from('shoppingCart as sc')
                ->where('sc.m_id', $_SESSION['Mid'])
                ->where('sc.l_id !=', 'null')
                ->join('live as l', 'sc.l_id = l.l_id', 'left')
                ->join('courseEvaluation as ce', 'sc.l_id = ce.l_id and sc.sc_id = ce.sc_id', 'left')
                ->join('teacher', 'sc.t_id = teacher.t_id', 'left')
                ->order_by('sc_date', 'DESC')
                ->get()->result();
        else
            return $this->db->select('courseFilm.*, ce.ce_id as courseEvaluationID, ce_comment as comment, ce_level as score, sc.m_id as studentID, t_name as teacherName, sc.sc_id as shoppingCartID')
                ->from('shoppingCart as sc')
                ->where('sc.m_id', $_SESSION['Mid'])
                ->where('sc.cf_id !=', null)
                ->join('courseFilm', 'sc.cf_id = courseFilm.cf_id', 'inner')
                ->where('cf_type !=', null)
                ->join('courseEvaluation as ce', 'sc.cf_id = ce.cf_id and sc.sc_id = ce.sc_id', 'left')
                ->join('teacher', 'sc.t_id = teacher.t_id', 'left')
                ->order_by('sc_date', 'DESC')
                ->get()->result();
    }

    public function changeComment($data = '', $l_id = '')
    {
        $this->db->trans_begin();
        $ceData = ($this->db
            ->select('ce_id, ce_level')
            ->from('courseEvaluation')
            ->where('l_id', $l_id)
            ->where('m_id', $_SESSION['Mid'])
            ->get()->row());
        if ($ceData == null)
            return false;

        if ($data['ce_comment'] == $ceData->ce_level) {
            $this->db->where('ce_id', $ceData->ce_id)->update('courseEvaluation', $data);
        } else {
            $this->db->where('ce_id', $ceData->ce_id)->update('courseEvaluation', $data);
            $evaluation = $this->getEvaluation($data['l_id']);
            $this->db->where('l_id', $data['l_id'])->update('live', array('l_evaluation' => $evaluation));
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            return true;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

}