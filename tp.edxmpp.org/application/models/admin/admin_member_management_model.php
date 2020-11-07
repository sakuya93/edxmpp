<?php

class admin_member_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getMemberData($type = '')
    {
        if ($type == 0)
            return $this->db->select('m_id AS id, m_type AS type, m_name AS name, m_photo AS photo')
                ->from('member')
                ->where('m_type', $type)
                ->or_where('m_type', null)
                ->get()->result();
        else
            return $this->db->select('m_id AS id, m_type AS type, m_name AS name, m_photo AS photo')
                ->from('member')
                ->where('m_type', $type)
                ->get()->result();
    }

    public function checkMemberIsNull($m_id = ''){
    	return $this->db->select('*')->from('member')->where('m_id', $m_id)->get()->num_rows();
	}

    public function blockade_or_unblockMember($id = '', $type = '')
    {
        return $this->db->where('m_id', $id)->update('member', array('m_type' => $type));
    }

    public function addBlockingReason($m_id = '', $reason = ''){
    	if($this->db->select('*')->from('blocking_reason')->where('m_id', $m_id)->get()->num_rows() > 0)
        	return $this->db->update('blocking_reason', array('m_id' => $m_id, 'br_blockingReason' => $reason));
    	else
    		return $this->db->insert('blocking_reason', array('m_id' => $m_id, 'br_blockingReason' => $reason));
    }

    public function deleteBlockingReason($m_id = ''){
        return $this->db->delete('blocking_reason', array('m_id'=> $m_id));
    }
}
