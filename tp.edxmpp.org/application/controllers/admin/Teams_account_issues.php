<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teams_account_issues extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
//        $this->load->model("admin/admin_Teams_account_issues_model", "Model", TRUE);
        $this->load->model("admin_Teams_account_issues_model");

    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/teams_account_issues_view', $HTML);
            $this->load->view('window/admin/teams_account_issues_window');
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function getTeacherData($type = '')
    {
        echo json_encode($this->Model->getTeacherData($type));
    }

    public function freedAccount()
    {
        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => 'Teams帳號不可為空'),
            2 => array('key' => 'not null', 'msg' => 'Teams密碼不可為空'),
            3 => array('key' => 'not null', 'msg' => '資料不完整'),
            4 => array('key' => 'not null', 'msg' => '資料不完整'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->Model->checkTeacherReview($data['id']) != 1) {
            echo json_encode(array('status' => false, 'msg' => '此位老師未通過審核，無法發放Teams帳號'));
            return;
        }
        $this->db->trans_begin();
        $update = array(
          't_teamsAccount' => $data['teamsAccount'],
          'application_key' => $data['applicationKey'],
          'list_key' => $data['listKey']
        );
        if ($this->Model->freedAccount($data['id'], $update)) {
            $teacherEmail = $this->Model->getTeacherEmail($data['id']);

            $this->load->library('email');
            $this->email->set_mailtype("html");
            $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
            $this->email->to($teacherEmail);
            $this->email->subject("XXX教學平台老師直播專用Teams帳號發放");

            $message = "<p style='color: #686868'>以下是您的Teams帳號密碼，用於線上直播教學請妥善保管!</p><br>
                        <table style=\"text-align: center;border-collapse: collapse;border: 1px dashed black;min-width: 300px\">
                            <thead>
                                <tr>
                                    <th style='padding:5px;color:#fff;border-bottom: 1px solid #ffffff;background-color:#ffba2d;'>帳號</th>
                                    <td style='padding: 10px 15px;color: #669;border-top: 1px dashed black;'>{$data['teamsAccount']}</td>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr>
                                    <th style='padding:5px;color:#fff;background-color:#ffba2d;'>密碼</th>
                                    <td style='padding: 10px 15px;color: #669;border-top: 1px solid #ffba2d;'>{$data['teamsPassword']}</td>
                                </tr>
                            </tbody>
                        </table>";

            $this->email->message($message);

            if ($this->db->trans_status() === TRUE & $this->email->send()) {
                $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '發放Teams帳號給老師，並且寄信通知'));
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '發放Teams帳號失敗，請刷新頁面重新嘗試'));
            }
        } else {

        }

    }
}
