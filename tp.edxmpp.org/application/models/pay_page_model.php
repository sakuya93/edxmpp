<?php

class pay_page_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getSalaryData(){
        return $this->db->select('rs_name AS name, rs_code AS code, rs_account AS account, rs_accountName AS accountName')
            ->from('receive_salary')
            ->where('t_id', $_SESSION['Tid'])
            ->get()->row();
    }

    public function getSalary(){
        return ($this->db->select('t_income')->from('teacher')->where('t_id', $_SESSION['Tid'])->get()->row())->t_income;
    }

    public function updateTeacherSalary($price = 0){
        return $this->db->query("UPDATE teacher SET t_income = t_income - {$price} WHERE t_id = '{$_SESSION['Tid']}'");
    }

    public function checkSalary(){
        return $this->db->select('*')->from('receive_salary')->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function addSalaryData($insert = ''){
        return $this->db->insert('receive_salary', $insert);
    }

    public function updateSalaryData($update = ''){
        return $this->db->where('t_id', $_SESSION['Tid'])->update('receive_salary', $update);
    }

    public function getDrawInto()
    { //取得目前平台抽成
        return $this->db->select('draw_into')->from('platform_earn')->get()->row();
    }
}