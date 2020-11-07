<?php

class leaveMessage extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkRepeatMessage()
    {

    }

    public function addLiveMessage()
    {
        //status: 0=>學生， 1=>老師
        $this->checkOneLogin();

        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            1 => array('key' => 'not null', 'msg' => '資料不完整'),
            2 => array('key' => 'not null', 'msg' => '資料不完整'),
            3 => array('key' => '', 'msg' => ''),
            4 => array('key' => 'not null', 'msg' => '留言不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($data['status'] == 1) {
            if (!isset($_SESSION['Tid'])) {
                echo json_encode(array('status' => false, 'msg' => '您不是老師，故無法在此頁面以老師身分回復'));
            }
            if ($this->db->select('*')->from('live')->where('l_id', $data['id'])->where('t_id', $_SESSION['Tid'])->get()->num_rows() != 1) {
                echo json_encode(array('status' => false, 'msg' => '此課程並非您的課程，故無法在此頁面以老師身分回復'));
                return;
            }
            $adminStatus = 0;
            $identity = 't_id';
            $identityID = $_SESSION['Tid'];
        } elseif ($data['status'] == 0) {
            if ($this->db->select('*')->from('live_message')->where('l_id', $data['id'])->like('lm_date', date('Y-m-d H'))->where('m_id', $_SESSION['Mid'])->get()->num_rows() > 5 & $data['replay'] == null) {
                echo json_encode(array('status' => false, 'msg' => '請勿重複留言，如有問題請聯繫老師或聯繫管理員'));
                return;
            }
            $adminStatus = 0;
            $identity = 'm_id';
            $identityID = $_SESSION['Mid'];
        } else {
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
            return;
        }

        if (isset($_SESSION['front_end_admin'])) {
            $adminStatus = 1;
            $identity = 't_id';
            $identityID = null;
        }

        if ($data['replay'] != null) {
            $insert = array(
                'lm_id' => $data['replay'],
                $identity => $identityID,
                'lmr_message' => $data['message'],
                'lmr_date' => date("Y-m-d H:i:s"),
                'lmr_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('live_message_reply', $insert))
                echo json_encode(array('status' => true, 'msg' => '回覆成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '回覆失敗，請刷新頁面重新嘗試'));
        } else {
            $insert = array(
                'l_id' => $data['id'],
                $identity => $identityID,
                'lm_message' => $data['message'],
                'lm_date' => date("Y-m-d H:i:s"),
                'lm_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('live_message', $insert))
                echo json_encode(array('status' => true, 'msg' => '留言成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '留言失敗，請刷新頁面重新嘗試'));
        }
    }

    public function addFilmMessage()
    {
        //status: 0=>學生， 1=>老師
        $this->checkOneLogin();

        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            1 => array('key' => 'not null', 'msg' => '資料不完整'),
            2 => array('key' => 'not null', 'msg' => '資料不完整'),
            3 => array('key' => '', 'msg' => ''),
            4 => array('key' => 'not null', 'msg' => '留言不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($this->db->select('*')->from('courseFilm')->where('cf_id', $data['id'])->get()->num_rows() < 1) {
            echo json_encode(array('status' => false, 'msg' => '無此課程資料，請刷新頁面後重新嘗試'));
            return;
        }
        if ($data['status'] == 1) {
            if (!isset($_SESSION['Tid'])) {
                echo json_encode(array('status' => false, 'msg' => '您不是老師，故無法在此頁面以老師身分回復'));
            }
            if ($this->db->select('*')->from('courseFilm')->where('cf_id', $data['id'])->where('t_id', $_SESSION['Tid'])->get()->num_rows() < 1) {
                echo json_encode(array('status' => false, 'msg' => '此課程並非您的課程，故無法在此頁面以老師身分回復'));
                return;
            }
            $adminStatus = 0;
            $identity = 't_id';
            $identityID = $_SESSION['Tid'];
        } elseif ($data['status'] == 0) {
            if ($this->db->select('*')->from('film_message')->where('cf_id', $data['id'])->like('fm_date', date('Y-m-d H'))->where('m_id', $_SESSION['Mid'])->get()->num_rows() > 5 & $data['replay'] == null) {
                echo json_encode(array('status' => false, 'msg' => '請勿重複留言，如有問題請聯繫老師或聯繫管理員'));
                return;
            }
            $adminStatus = 0;
            $identity = 'm_id';
            $identityID = $_SESSION['Mid'];
        } else {
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
            return;
        }

        if (isset($_SESSION['front_end_admin'])) {
            $adminStatus = 1;
            $identity = 't_id';
            $identityID = null;
        }

        if ($data['replay'] != null) {
            $insert = array(
                'fm_id' => $data['replay'],
                $identity => $identityID,
                'fmr_message' => $data['message'],
                'fmr_date' => date("Y-m-d H:i:s"),
                'fmr_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('film_message_reply', $insert))
                echo json_encode(array('status' => true, 'msg' => '回覆成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '回覆失敗，請刷新頁面重新嘗試'));
        } else {
            $insert = array(
                'cf_id' => $data['id'],
                $identity => $identityID,
                'fm_message' => $data['message'],
                'fm_date' => date("Y-m-d H:i:s"),
                'fm_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('film_message', $insert))
                echo json_encode(array('status' => true, 'msg' => '留言成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '留言失敗，請刷新頁面重新嘗試'));
        }


    }

    public function addFundraisingMessage()
    {
        //status: 0=>學生， 1=>老師
        $this->checkOneLogin();

        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            1 => array('key' => 'not null', 'msg' => '資料不完整'),
            2 => array('key' => 'not null', 'msg' => '資料不完整'),
            3 => array('key' => '', 'msg' => ''),
            4 => array('key' => 'not null', 'msg' => '留言不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($this->db->select('*')->from('fundraising_course')->where('fc_id', $data['id'])->get()->num_rows() != 1) {
            echo json_encode(array('status' => false, 'msg' => '無此課程資料，請刷新頁面後重新嘗試'));
            return;
        }
        if ($data['status'] == 1) {
            if (!isset($_SESSION['Tid'])) {
                echo json_encode(array('status' => false, 'msg' => '您不是老師，故無法在此頁面以老師身分回復'));
            }
            if ($this->db->select('*')->from('fundraising_course')->where('fc_id', $data['id'])->where('t_id', $_SESSION['Tid'])->get()->num_rows() != 1) {
                echo json_encode(array('status' => false, 'msg' => '此課程並非您的課程，故無法在此頁面以老師身分回復'));
                return;
            }
            $adminStatus = 0;
            $identity = 't_id';
            $identityID = $_SESSION['Tid'];
        } elseif ($data['status'] == 0) {
            if ($this->db->select('*')->from('fundraisingCourse_message')->where('fc_id', $data['id'])->where('m_id', $_SESSION['Mid'])->like('fcm_date', date('Y-m-d H'))->get()->num_rows() > 5 & $data['replay'] == null) {
                echo json_encode(array('status' => false, 'msg' => '請勿重複留言，如有問題請聯繫老師或聯繫管理員'));
                return;
            }
            $adminStatus = 0;
            $identity = 'm_id';
            $identityID = $_SESSION['Mid'];
        } else {
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面重新嘗試'));
            return;
        }

        if (isset($_SESSION['front_end_admin'])) {
            $adminStatus = 1;
            $identity = 't_id';
            $identityID = null;
        }

        if ($data['replay'] != null) {
            $insert = array(
                'fcm_id' => $data['replay'],
                $identity => $identityID,
                'fcmr_message' => $data['message'],
                'fcmr_date' => date("Y-m-d H:i:s"),
                'fcmr_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('fundraisingCourse_message_reply', $insert))
                echo json_encode(array('status' => true, 'msg' => '回覆成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '回覆失敗，請刷新頁面重新嘗試'));
        } else {
            $insert = array(
                'fc_id' => $data['id'],
                $identity => $identityID,
                'fcm_message' => $data['message'],
                'fcm_date' => date("Y-m-d H:i:s"),
                'fcm_adminStatus' => $adminStatus,
            );
            if ($this->db->insert('fundraisingCourse_message', $insert))
                echo json_encode(array('status' => true, 'msg' => '留言成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '留言失敗，請刷新頁面重新嘗試'));
        }


    }

}
