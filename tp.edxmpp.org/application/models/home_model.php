<?php

class home_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkRegistered($account = '')
    {
        $obj = new stdClass();
        $this->db
            ->select("*")
            ->from('member')
            ->where('m_account', $account);
        $result = $this->db->get();
        $this->db->flush_cache();
        if ($result->num_rows() != 0) {
            $obj->type = false;
            $obj->msg = $GLOBALS['controllerLang']['modal']['email_already_registered'];
        }else
            $obj->type = true;
        return $obj;
    }

    public function registered($dataArray){
        if($this->db->insert('member', $dataArray)) {
            return true;
        }else
            return false;
    }

    public function login($dataArray, $ip){
        $obj = new stdClass();
        $this->db
            ->select("member.m_id as Mid, m_account as user_name, t_id as Tid")
            ->from('member')
            ->where('m_account', $dataArray['account'])
            ->where('m_password', sha1($dataArray['password']))
            ->join('main', 'member.m_id = main.m_id', 'left');
        $result = $this->db->get();
        $this->db->flush_cache();
        if($result->num_rows() != 0){
            $obj->type = true;
            $obj->data =  $result->row();
            $this->db->where('m_id', $obj->data->Mid)->update('member', array('IP' => $ip));
        }
        else
            $obj->type = false;

        return $obj;
    }

    public function getBlockingReason($m_id = ''){
        if($this->db->select('*')->from('blocking_reason')->where('m_id', $m_id)->get()->num_rows() > 0)
            return $this->db->select('br_blockingReason AS blockingReason')->from('blocking_reason')->where('m_id', $m_id)->get()->row();
        return null;
    }
}