<?php

class teacher_salary_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

        public function getSalaryDataStatus0(){
            return $this->db->select('receive_salary.t_id AS teacherID, t_name AS teacherName, rs_name AS name, rs_code AS code, rs_date AS date,
     rs_account AS account, rs_accountName AS accountName, FORMAT((t_income - (t_income * 0.03)), 2) AS price, rs_status AS status, rs_id AS id')
                ->from('receive_salary')
                ->where('rs_status', null)
                ->where('t_income >=', 100)
                ->or_where('rs_status !=', date("m"))
                ->join('teacher', 'teacher.t_id = receive_salary.t_id', 'inner')
                ->get()->result();
    }

    public function getSalaryDataStatus1(){
        return $this->db->select('receive_salary.t_id AS teacherID, t_name AS teacherName, rs_name AS name, rs_code AS code, rs_date AS date,
     rs_account AS account, rs_accountName AS accountName, FORMAT((rs_price - (rs_price * 0.03)), 2) AS price, rs_status AS status, rs_id AS id')
            ->from('receive_salary')
            ->where('rs_status', date("m"))
            ->join('teacher', 'teacher.t_id = receive_salary.t_id', 'inner')
            ->get()->result();
    }

    public function getSalaryPrice($id = ''){
        return $this->db->select('t_income AS price, receive_salary.t_id')->from('receive_salary')->where('rs_id', $id)
            ->join('teacher', 'teacher.t_id = receive_salary.t_id', 'inner')
            ->get()->row();
    }

    public function updateSalaryStatus($id = '', $data = 0){
        $this->db->where('t_id', $data->t_id)->update('teacher', array('t_income' => 0));
        return $this->db->where('rs_id', $id)->update('receive_salary', array('rs_status' => date('m'), 'rs_price' => $data->price));
    }

    public function getSalaryDetail($id = ''){
        return $this->db->select('receive_salary.t_id AS teacherID, t_name AS teacherName, rs_name AS name, rs_code AS code, rs_date AS date,
     t_photo AS photo, rs_account AS account, rs_accountName AS accountName, FORMAT((t_income - (t_income * 0.03)), 2) AS price, rs_status AS status')
            ->from('receive_salary')
            ->where('rs_id', $id)
            ->join('teacher', 'teacher.t_id = receive_salary.t_id', 'inner')
            ->get()->result();
    }
}