<?php

class admin_Report_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getClassReport($date = ''){
        $select =  $this->db->select('t_name AS reportedName, m_name AS reportName, t_id AS managementID,
        t_photo AS reportedPhoto, m_photo AS reportPhoto, ROW_NUMBER() OVER (PARTITION BY reported_id ORDER BY r_date DESC) AS sn,
        report_id AS report, reported_id AS reported, r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->like('r_date', $date)
            ->where('r_option >=', 4)
            ->where('r_option <=', 9)
            ->join('teacher', 'report.reported_id = teacher.t_Id', 'inner')
            ->join('member', 'report.report_id = member.m_id', 'inner')
            ->get_compiled_select();
        return $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC")->result();
    }

    public function getClassReportDetail($reported = ''){
        return $this->db->select('m_name AS reportName, IF(report.l_id, "1", "0") AS courseType, IFNULL(report.l_id, report.cf_id) AS courseID,
        m_photo AS reportPhoto, report_id AS report, reported_id AS reported,
        IF(report.l_id, "https://ajcode.tk/teaching_platform_dev/Teacher_sales/live/", "https://ajcode.tk/teaching_platform_dev/film_courses/") AS coursePath,
         r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->where('reported_id', $reported)
            ->join('member', 'report.report_id = member.m_id', 'inner')
            ->order_by('r_date', 'DESC')
            ->get()->result();
    }

    public function getStudentReport($date = ''){
        $select =  $this->db->select('m_name AS reportedName, m_id AS managementID,
        m_photo AS reportedPhoto, ROW_NUMBER() OVER (PARTITION BY reported_id ORDER BY r_date DESC) AS sn,
        report_id AS report, reported_id AS reported, r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->like('r_date', $date)
            ->where('r_option <=', 3)
            ->join('member', 'report.reported_id = member.m_id', 'inner')
            ->get_compiled_select();
        return $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC")->result();
    }

    public function getStudentReportDetail($reported = ''){
        return $this->db->select('m_name AS reportName, IF(report.l_id, "1", "0") AS courseType,
        m_photo AS reportPhoto, report_id AS report, reported_id AS reported,
        "https://ajcode.tk/teaching_platform_dev/dashboard/" AS coursePath,
         r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->where('reported_id', $reported)
            ->join('member', 'report.report_id = member.m_id', 'left')
            ->order_by('r_date', 'DESC')
            ->get()->result();
    }

    public function getReportRecord($date = ''){
        $select =  $this->db->select('m_name AS reportedName, m_id AS managementID,
        m_photo AS reportedPhoto, ROW_NUMBER() OVER (PARTITION BY report_id ORDER BY r_date DESC) AS sn,
        report_id AS report, reported_id AS reported, r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->like('r_date', $date)
            ->join('member', 'report.report_id = member.m_id', 'inner')
            ->get_compiled_select();
        return $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC")->result();
    }

    public function getReportRecordDetail($report = ''){
        return $this->db->select('IFNULL(m_name, t_name) AS reportName, IF(report.l_id, "1", "0") AS courseType,
        IFNULL(m_photo, t_photo) AS reportPhoto, report_id AS report, reported_id AS reported, IFNULL(t_id, m_id) AS reportedID,
        IF(t_id, "https://ajcode.tk/teaching_platform_dev/teacher_page/", "https://ajcode.tk/teaching_platform_dev/dashboard/") AS reportedPath,
        IFNULL(l_id, cf_id) AS courseID, IF(l_id, "https://ajcode.tk/teaching_platform_dev/Teacher_sales/live/", "https://ajcode.tk/teaching_platform_dev/film_courses/") AS coursePath,
         r_option AS option, r_content AS content, r_date AS date')
            ->from('report')
            ->where('report_id', $report)
            ->join('member', 'report.reported_id = member.m_id', 'left')
            ->join('teacher', 'report.reported_id = teacher.t_id', 'left')
            ->order_by('r_date', 'DESC')
            ->get()->result();
    }
}