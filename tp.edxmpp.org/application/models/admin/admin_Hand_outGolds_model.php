<?php

class admin_Hand_outGolds_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkMemberIsNull($mid){
        return $this->db->select('*')->from('main')->where('m_id', $mid)->get()->num_rows();
    }

    public function getRandomMember($quota = 1){ //取得隨機幸運兒
        return $this->db->select('m_id as id')->from('main')
            ->distinct()
            ->order_by('m_id', 'RANDOM')
            ->limit($quota)
            ->get()
            ->result();
    }

    public function handOutGoldsAll($gold = 0){
        $gold = (int)$gold;
        return $this->db->query("UPDATE main SET gold = gold + {$gold}");
    }

    public function handOutGolds($data = ''){
        return $this->db->query("UPDATE main SET gold = gold + {$data['gold']} WHERE m_id = '{$data['id']}'");
    }

    public function addHandOutGoldsNotice($insert = ''){
        return $this->db->insert('notice_record', $insert);
    }

    public function addHandOutGoldsRecord($insert = ''){
        return $this->db->insert('gold_release_record', $insert);
    }

    public function getHandOutGoldsRecord($date = ''){
        return $this->db->select('grr_id AS id, grr_acceptID AS acceptID, grr_point AS gold, grr_date AS date, m_photo AS photo, m_name AS name')
            ->from('gold_release_record')
            ->like('grr_date', $date)
            ->join('member', 'gold_release_record.grr_acceptID = member.m_id', 'left')
            ->order_by('grr_date', 'DESC')
            ->get()->result();
    }

    public function getHandOutGoldsRecordSpecific($m_id = ''){
        return $this->db->select('grr_id AS id, grr_acceptID AS acceptID, grr_point AS gold, grr_date AS date, m_photo AS photo, m_name AS name')
            ->from('diamond_release_record')
            ->where('acceptID', $m_id)
            ->join('member', 'gold_release_record.grr_acceptID = member.m_id', 'left')
            ->order_by('grr_date', 'DESC')
            ->get()->result();
    }

    public function getMemberData($m_id = ''){
        return $this->db->select('m_photo AS photo, m_name AS name')->from('member')->where('m_id', $m_id)->get()->row();
    }

    public function getRecordData($id = ''){
        return $this->db->select('*')->from('gold_release_record')->where('grr_id', $id)->get()->row();
    }

    public function deductionMemberGolds($data = ''){
        if($data->grr_acceptID == 'all'){
            return $this->db->query("UPDATE main SET gold = gold - {$data->grr_point}");
        }else{
            return $this->db->query("UPDATE main SET gold = gold - {$data->grr_point} WHERE m_id = '{$data->grr_acceptID}'");
        }
    }

    public function deleteHandOutGoldsRecord($data = ''){
        return $this->db->delete('gold_release_record', array('grr_id' => $data->grr_id));
    }
}