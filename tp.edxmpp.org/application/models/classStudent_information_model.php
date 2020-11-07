<?php

class classStudent_information_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkComment($data = '')
    {
        if ($this->db->select('*')->from('courseEvaluation')->where('sc_id', $data['sc_id'])->where('t_id', $_SESSION['Tid'])->get()->num_rows() != 0)
            return true;
        else
            return false;
    }

    public function addComment($data = '')
    {
        return $this->db->insert('courseEvaluation', $data);
    }

    public function deleteComment($id = '', $id2 = '')
    {
        return $this->db->delete('courseEvaluation', array('sc_id' => $id, 't_id' => $_SESSION['Tid']));
    }

    public function changeComment($data = '', $id = ''){
        return $this->db->where('ce_id', $id)->where('t_id', $_SESSION['Tid'])->update('courseEvaluation', $data);
    }

    public function getLiveComment($courseType = ''){ //取得直播課程有購買這個老師的課程的學生
        $courseType = urldecode($courseType);

        return $this->db->select('member.m_name as memberName, member.m_id as memberID, member.m_photo as photo, sc.sc_className as className, ce.*,
         sc.sc_id as shoppingCartID, sc.l_id as id')
            ->from('shoppingCart as sc')
            ->where('sc.t_id', $_SESSION['Tid'])
            ->where('sc.cf_id', null)
            ->where('sc.sc_payStatus', '1')
            ->join('member', 'sc.m_id = member.m_id', 'left')
            ->join('courseEvaluation as ce', 'sc.t_id = ce.t_id and sc.sc_id = ce.sc_id', 'left')
            ->join('live', 'live.l_id = sc.l_id', 'left')
            ->where('l_type', $courseType)
            ->get()->result();
    }

    public function getFilmComment(){ //取得影片課程有購買這個老師的課程的學生
        return $this->db->select('member.m_name as memberName, member.m_id as memberID, member.m_photo as photo, sc.sc_className as className, ce.*,
         sc.sc_id as shoppingCartID')
            ->from('shoppingCart as sc')
            ->where('sc.t_id', $_SESSION['Tid'])
            ->where('sc.cf_id !=', '')
            ->where('sc.sc_payStatus', '1')
            ->join('member', 'sc.m_id = member.m_id', 'left')
            ->join('courseEvaluation as ce', 'sc.t_id = ce.t_id and sc.sc_id = ce.sc_id', 'left')
            ->get()->result();
    }

    public function getOption(){
        return $this->db->select('option')->from('classOption')->get()->result();
    }
}