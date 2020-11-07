<?php

class admin_signIn_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function signIn($data = ''){
        if($this->db->select('*')->from('admin')->where('account', $data['account'])->where('password', $data['password'])->get()->num_rows())
            return true;
        else
            return false;
    }
}