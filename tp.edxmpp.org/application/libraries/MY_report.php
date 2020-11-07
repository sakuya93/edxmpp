<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_report extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function addClassReport($data = '')
    {
        $option = (int)$data['option'];
        if ($option < 4 | $option > 9) {
            return json_encode(array('status' => false, 'msg' => '沒有此檢舉選項，請重新嘗試'));
            return;
        }

        //course_type 0 => 直播、1 => 影片
        if ($data['course_type'] == 0) {
            $course_key = 'l_id';
            if ($this->db->select('*')->from('live')->where('l_id', $data['id'])->where('t_id', $_SESSION['Tid'])
                    ->get()->num_rows() > 0) {
                echo json_encode(array('status' => false, 'msg' => '這是您的課程，無法檢舉'));
                return;
            }
            if ($this->db->select('*')->from('shoppingCart')->where('l_id', $data['id'])
                    ->where('m_id', $_SESSION['Mid'])->get()->num_rows() < 1) {
                return json_encode(array('status' => false, 'msg' => '您未購買這堂課程，故無法對此課程進行檢舉'));
                return;
            }
            $Tid = ($this->db->select('t_id')->from('live')->where('l_id', $data['id'])->get()->row())->t_id;
            if ($Tid == null) {
                return json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
                return;
            }

        } elseif ($data['course_type'] == 1) {
            $course_key = 'cf_id';
            if ($this->db->select('*')->from('courseFilm')->where('cf_id', $data['id'])->where('t_id', $_SESSION['Tid'])
                    ->get()->num_rows() > 0) {
                echo json_encode(array('status' => false, 'msg' => '這是您的課程，無法檢舉'));
                return;
            }
            if ($this->db->select('*')->from('shoppingCart')->where('cf_id', $data['id'])
                    ->where('m_id', $_SESSION['Mid'])->get()->num_rows() < 1) {
                return json_encode(array('status' => false, 'msg' => '您未購買這堂課程，故無法對此課程進行檢舉'));
                return;
            }
            $Tid = ($this->db->select('t_id')->from('courseFilm')->where('cf_id', $data['id'])->get()->row())->t_id;
            if ($Tid == null) {
                return json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
                return;
            }
        } else {
            return json_encode(array('status' => false, 'msg' => '參數不正確，請刷新頁面重新嘗試'));
            return;
        }
        $insert = array(
            $course_key => $data['id'],
            'report_id' => $_SESSION['Mid'],
            'reported_id' => $Tid,
            'r_option' => $data['option'],
            'r_content' => $data['content'],
            'r_date' => date("Y/m/d H:i:s")
        );
        if ($this->db->select('*')->from('report')->where('report_id', $_SESSION['Mid'])->where('reported_id', $Tid)->where('r_option', $data['option'])->where($course_key, $data['id'])->get()->num_rows() == 1) {
            return json_encode(array('status' => false, 'msg' => '您以重複檢舉相關內容，請勿重複檢舉'));
            return;
        }

        if ($this->db->insert('report', $insert))
            return json_encode(array('status' => true, 'msg' => '感謝您的檢舉，我們會盡最大努力進行改善'));
        else
            return json_encode(array('status' => false, 'msg' => '檢舉失敗，請刷新頁面重新嘗試'));
    }

    public function addMemberReport($data = '')
    {
        $option = (int)$data['option'];
        if ($option < 1 | $option > 4) {
            return json_encode(array('status' => false, 'msg' => '沒有此檢舉選項，請重新嘗試'));
            return;
        }

        if ($this->db->select('*')->from('member')->where('m_id', $data['reported'])->get()->num_rows() != 1) {
            echo json_encode(array('status' => false, 'msg' => '不明錯誤，請刷新頁面重新嘗試'));
            return;
        }
        $insert = array(
            'report_id' => $_SESSION['Mid'],
            'reported_id' => $data['reported'],
            'r_option' => $data['option'],
            'r_content' => $data['content'],
            'r_date' => date("Y/m/d H:i:s")
        );
        if ($this->db->select('*')->from('report')->where('report_id', $_SESSION['Mid'])->where('reported_id', $data['reported'])->where('r_option', $data['option'])->get()->num_rows() > 0) {
            return json_encode(array('status' => false, 'msg' => '您以重複檢舉相關內容，請勿重複檢舉'));
            return;
        }

        if ($this->db->insert('report', $insert))
            return json_encode(array('status' => true, 'msg' => '感謝您的檢舉，我們會盡最大努力進行改善'));
        else
            return json_encode(array('status' => false, 'msg' => '檢舉失敗，請刷新頁面重新嘗試'));
    }
}