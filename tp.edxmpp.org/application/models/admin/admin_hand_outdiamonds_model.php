<?php

class admin_Hand_outDiamonds_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkMemberIsNull($mid){
        return $this->db->select('*')->from('main')->where('m_id', $mid)->get()->num_rows();
    }

    public function handOutDiamondsAll($point = 0){
        $point = (int)$point;
        return $this->db->query("UPDATE main SET points = points + {$point}");
    }

    public function handOutDiamonds($data = ''){
        return $this->db->query("UPDATE main SET points = points + {$data['point']} WHERE m_id = '{$data['id']}'");
    }

    public function addHandOutDiamondsNotice($insert = ''){
        return $this->db->insert('notice_record', $insert);
    }

    public function addHandOutDiamondsRecord($insert = ''){
        return $this->db->insert('diamond_release_record', $insert);
    }

    public function getHandOutDiamondsRecord($date = ''){
        return $this->db->select('ddr_id AS id, ddr_acceptID AS acceptID, ddr_point AS point, ddr_date AS date, m_photo AS photo, m_name AS name')
            ->from('diamond_release_record')
            ->like('ddr_date', $date)
            ->join('member', 'diamond_release_record.ddr_acceptID = member.m_id', 'left')
            ->order_by('ddr_date', 'DESC')
            ->get()->result();
    }

    public function getHandOutDiamondsRecordSpecific($m_id = ''){
        return $this->db->select('ddr_id AS id, ddr_acceptID AS acceptID, ddr_point AS point, ddr_date AS date, m_photo AS photo, m_name AS name')
            ->from('diamond_release_record')
            ->where('acceptID', $m_id)
            ->join('member', 'diamond_release_record.ddr_acceptID = member.m_id', 'left')
            ->order_by('ddr_date', 'DESC')
            ->get()->result();
    }

    public function getMemberData($m_id = ''){
        return $this->db->select('m_photo AS photo, m_name AS name')->from('member')->where('m_id', $m_id)->get()->row();
    }

    public function getRecordData($id = ''){
        return $this->db->select('*')->from('diamond_release_record')->where('ddr_id', $id)->get()->row();
    }

    public function deductionMemberDiamonds($data = ''){
        if($data->ddr_acceptID == 'all'){
            return $this->db->query("UPDATE main SET points = points - {$data->ddr_point}");
        }else{
            return $this->db->query("UPDATE main SET points = points - {$data->ddr_point} WHERE m_id = '{$data->ddr_acceptID}'");
        }
    }

    public function deleteHandOutDiamondsRecord($data = ''){
        return $this->db->delete('diamond_release_record', array('ddr_id' => $data->ddr_id));
    }
}