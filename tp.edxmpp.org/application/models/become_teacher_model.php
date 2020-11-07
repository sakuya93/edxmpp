<?php

class become_teacher_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getSimple_data()
    {
        if (isset($_SESSION['Tid'])) {
            return $this->db->select('t_name as name, t_country as country, t_speakLanguage as speak_language, t_veryShort_des as very_short_des, t_short_des as short_des, t_des as des')
                ->from('teacher')
                ->where('t_id', $_SESSION['Tid'])
                ->get()->row();
        } else
            return null;
    }

    public function getComplex_data()
    {
        if (isset($_SESSION['Tid'])) {
            return $this->db->select('w_id as id, w_start_date as start_date, w_end_date as end_date, w_company_name as company_name, w_service_content as service_content')
                ->from('work_experience')
                ->where('t_id', $_SESSION['Tid'])
                ->get()->result();
        } else
            return null;
    }

    public function getEducation_data()
    {
        if (isset($_SESSION['Tid'])) {
            return $this->db->select('ed_id as id, e_start_date as start_date, e_end_date as end_date, e_school_name as school_name, e_department_name as department_name, e_certified_documents as certified_documents')
                ->from('education_background')
                ->where('t_id', $_SESSION['Tid'])
                ->get()->result();
        } else
            return null;
    }

    public function getEducation_imageName()
    {
        if (isset($_SESSION['Tid'])) {
            return $this->db->select('e_certified_documents as name')
                ->from('education_background')
                ->where('t_id', $_SESSION['Tid'])
                ->get()->row();
        } else
            return null;
    }

    public function teacher_identification()
    {
        if ($this->db->select('t_id')->from('main')->where('m_id', $_SESSION['Mid'])->where('t_id !=', 'null')->get()->row() != null)
            return true;
        else
            return false;
    }

    public function basic_information($dataArray)
    {
        if ($this->db->where('t_id', $_SESSION['Tid'])->update('teacher', $dataArray))
            return true;
        else
            return false;
    }

    public function first_basic_information($dataArray)
    {
        $this->db->trans_begin();
        $result = $this->db
            ->select('m_photo')
            ->from('member')
            ->where('m_id', $_SESSION['Mid'])
            ->get()->row();
        $dataArray['t_photo'] = $result->m_photo;

        if ($this->db->insert('teacher', $dataArray))
            if ($this->db->where('m_id', $_SESSION['Mid'])->update('main', array('t_id' => $dataArray['t_id']))) {

            }
        if ($this->db->trans_status() === true) {
            $_SESSION['Tid'] = $dataArray['t_id'];
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }

    }

    public function teacher_introduction($dataArray)
    {
        if ($this->db->where('t_id', $_SESSION['Tid'])->update('teacher', $dataArray))
            return true;
        else
            return false;
    }

    public function work_experience($dataArray)
    {
        if (!$this->db->where('t_id', $_SESSION['Tid'])->delete('work_experience'))
            return false;
        if ($this->db->insert_batch('work_experience', $dataArray))
            return true;
        else
            return false;
    }

    public function modifyWorkExperience($Wid = '', $data = '')
    {
        return $this->db->where('w_id', $Wid)->update('work_experience', $data);
    }

    public function deleteWorkExperience($Wid = '')
    {
        return $this->db->where('w_id', $Wid)->delete('work_experience');
    }


    public function education_background($dataArray)
    {
        if ($this->db->insert_batch('education_background', $dataArray))
        	return true;
         else
            return false;
    }

    public function edit_education_background($id = '', $dataArray = null)
    {
        if ($this->db->where('ed_id', $id)->update('education_background', $dataArray))
            return true;

        return false;
    }

    public function get_ed_imageName($id = '')
    {
        $image = $this->db->select('e_certified_documents')->from('education_background')->where('ed_id', $id)->get()->row();
        return $image->e_certified_documents;
    }

    public function delete_education_background($id = '')
    {
        $image = $this->db->select('e_certified_documents')->from('education_background')->where('ed_id', $id)->get()->row();
        $image = $image->e_certified_documents;
        $image_path = 'resource/image/student/education_prove/';
        if (file_exists("{$image_path}{$image}")) {
            unlink("{$image_path}{$image}");
            if ($this->db->delete('education_background', array('ed_id' => $id)))
                return true;
        }
        return false;

    }

    public function education_background_count()
    {
        return $this->db->select('*')->from('education_background')->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function delete_work_experience_data()
    {
        return $this->db->delete('work_experience', array('t_id' => $_SESSION['Tid']));
    }

    public function get_teaching_license()
    {
        if (isset($_SESSION['Tid']))
            return $this->db->select('tl_id as id, tl_license_name as name, tl_file as file')->from('teaching_license')->where('t_id', $_SESSION['Tid'])->get()->result();
        return null;
    }

    public function teaching_license_count()
    {
        return $this->db->select('*')->from('teaching_license')->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function teaching_license($dataArray)
    {

        if (isset($dataArray)) {

            if ($this->db->insert_batch('teaching_license', $dataArray)) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function delete_teaching_license_data($id = null)
    {
        $imageName = $this->db->select('tl_file as file, tl_id as id')->from('teaching_license')->where('tl_id', $id)->get()->row();

        if (isset($id))
            $this->db->delete('teaching_license', array('tl_id' => $id));
        return $imageName;
    }

    public function edit_teaching_license($id = '', $dataArray = null)
    {
        if ($this->db->where('tl_id', $id)->update('teaching_license', $dataArray))
            return true;

        return false;
    }

    public function get_tl_imageName($id = '')
    {
        $image = $this->db->select('tl_file')->from('teaching_license')->where('tl_id', $id)->get()->row();
        return $image->tl_file;
    }
}
