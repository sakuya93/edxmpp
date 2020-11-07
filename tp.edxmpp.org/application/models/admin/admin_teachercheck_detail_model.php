<?php

class admin_teachercheck_detail_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getDetailedCheck($id = '')
    {
        $data = new stdClass();
        $data->singelData = $this->db->select('
        t_id as id, t_name as name, t_country as country, t_speakLanguage as speakLanguage, t_photo as photo,
        t_veryShort_des as veryShort_des, t_short_des as short_des, t_des as des')
            ->from('teacher')->where('t_id', $id)->get()->row();
        $data->work = $this->db->select('w_start_date as start_date, w_end_date as end_date, w_company_name as company_name, w_service_content as service_content')
            ->from('work_experience')->where('t_id', $id)->get()->result();
        $data->education = $this->db->select('e_start_date as start_date, e_end_date as end_date, e_school_name as school_name, e_department_name as department_name, e_certified_documents as certified_documents')
            ->from('education_background')->where('t_id', $id)->get()->result();
        $data->teaching = $this->db->select('tl_license_name as license_name, tl_file as file')
            ->from('teaching_license')->where('t_id', $id)->get()->result();
        
        return $data;
    }

    public function checkTeacherIsNull($t_id = ''){
        return $this->db->select('*')->from('teacher')->where('t_id', $t_id)->get()->num_rows();
    }
}