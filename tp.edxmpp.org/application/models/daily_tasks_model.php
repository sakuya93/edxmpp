<?php

class daily_tasks_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function checkEmailCertification(){
        return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->where('emailStatus', '1')->get()->num_rows();
    }

    public function addGold($gold = 0, $isNull = ''){
        if($isNull == null)
            return $this->db->where('m_id', $_SESSION['Mid'])->update('main', array('gold' => $gold));
        else
            return $this->db->query("UPDATE main SET gold = gold + {$gold} WHERE m_id = '{$_SESSION['Mid']}'");
    }

    public function getGold(){
        return $this->db->select('gold')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row();
    }

    public function lessGold(){
        return $this->db->query("UPDATE main SET gold = 0 WHERE m_id = '{$_SESSION['Mid']}'");
    }

    public function addDiamond(){
        return $this->db->query("UPDATE main SET points = points + 5 WHERE m_id = '{$_SESSION['Mid']}'");
    }

    public function addDailyCheckIn($task = ''){
        return $this->db->where('m_id', $_SESSION['Mid'])->update('main', array("{$task}" => date('d')));
    }

    public function checkReceive($task = ""){
        if($task == "task2" || $task == "task3")
            return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->where("'{$task}'", null)->get()->num_rows();
        else
            return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->where("'{$task}'", date('d'))->get()->num_rows();
    }

    public function getReceiveData(){
        return $this->db->select('task1, task2, task3')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row();
    }
}