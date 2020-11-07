<?php

class modify_member_information_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();

    }

    public function modify_member_information($data)
    {
        if ($this->db->where('m_account', $_SESSION['user_name'])->update('member', $data)) {
            if ($this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->get()->num_rows() == 0) {
                $this->db->insert('main', array('m_id' => $_SESSION['Mid']));
            }
            $this->db->flush_cache();
            return true;
        } else
            return false;
    }

    public function checkMemberInformation()
    {
        return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->get()->num_rows();
    }

    public function get_member_information()
    {
        $this->db
            ->select('*')
            ->from('member')
            ->where('m_account', @$_SESSION['user_name']);
        $result = $this->db->get();
        $this->db->flush_cache();
        if ($result->num_rows() != 0) {
            return $result->row();
        }
    }

    public function getMember_uuid($data)
    {
        $obj = new stdClass();
        $this->db
            ->select('m_id')
            ->from('member')
            ->where('m_account', $data);
        $result = $this->db->get();
        $this->db->flush_cache();
        if ($result->num_rows() != 0) {
            return $result->row()->m_id;
        }
    }

    public function upload_image($image)
    {
        $data = array(
            'm_photo' => $image['orig_name']
        );
        $data2 = array(
            't_photo' => $image['orig_name']
        );
        if ($this->db->where('m_account', $_SESSION['user_name'])->update('member', $data)) {
            if (isset($_SESSION['Tid']))
                $this->db->where('t_id', $_SESSION['Tid'])->update('teacher', $data2);
            return true;
        }
        return false;
    }

    public function check_password($old_password)
    {
        $this->db
            ->select('*')
            ->from('member')
            ->where('m_account', $_SESSION['user_name'])
            ->where('m_password', sha1($old_password));
        $result = $this->db->get();
        $this->db->flush_cache();
        if ($result->num_rows() != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function change_password($new_password)
    {
        $data = array(
            'm_password' => sha1($new_password)
        );
        $this->db->where('m_account', $_SESSION['user_name'])->update('member', $data);
    }

    public function emailRecord()
    {
        $uuid = sha1(uniqid());
        $data = array(
            'email_uuid' => $uuid,
            'email_date' => date('Y-m-d H:i', strtotime('+10 minute')),
        );
        $this->db->where('m_id', $_SESSION['Mid'])->update('member', $data);
        return "https://tp.edxmpp.org/modify_member_information/emailPass/$uuid";
    }

    public function emailPass($email = '')
    {
        $date = $this->db->select('email_date')->from('member')->where('m_id', $_SESSION['Mid'])->where('email_uuid', $email)->get()->row();
        if (!isset($date->email_date))
            return false;
        if (strtotime($date->email_date) > strtotime(date('Y-m-d H:i'))) {
            $this->db->where('m_id', $_SESSION['Mid'])->update('main', array('emailStatus' => '1'));
            return true;
        }
        return false;
    }

    public function updateEmail($email = '')
    {
        return $this->db->where('m_id', $_SESSION['Mid'])->update('member', array('m_email' => $email));
    }

    public function checkEmailPass()
    {
        return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->where('emailStatus', '1')->get()->num_rows();
    }

//    public function checkMemberInformation($email = '') //上面有重複定義方法(會錯誤)
//    {
//        return $this->db->select('*')->from('');
//    }

    public function updateTeamsAccount($teamsAccount = '')
    {
    return $this->db->where('m_id', $_SESSION['Mid'])->update('member', array('m_teamsAccount' => $teamsAccount));
    }
}