<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay_page extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("pay_page_model", "Model", TRUE);
    }

    public function index()
    {
        if(!isset($_SESSION['Tid']))
            redirect(base_url('student'));
        $data = new stdClass();
        if ($this->getlogin()) {
////////////////////////////////          必要資料確認及匯入資訊 Start          ///////////////////////////////
            $this->checkOneLogin();
            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data->course_management_link = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data->name = $name != '' ? $name : 'XXX';
            $data->photo_path = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';

//            $data->classOption = $this->getClassOption('');
            $data->RightInformationColumn = $this->getRightInformationColumn('', $data->photo_path, $data->name);

            $data->headerRightBar = $this->getHeaderRightBar('', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('');
            $data->salary = $this->Model->getSalary();
            $data->salaryData = $this->Model->getSalaryData();
            //此部分為取得平台目前抽成
            $data->drawInto = $this->Model->getDrawInto()->draw_into; //抽成%數
////////////////////////////////          必要資料確認及匯入資訊 End          ///////////////////////////////

            $this->load->view('teacher/pay_page_view', $data);
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function addGetSalary(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '銀行/郵局名稱不可為空'),
            1 => array('key' => 'not null', 'msg' => '銀行/郵局代碼不可為空'),
            2 => array('key' => 'not null', 'msg' => '銀行/郵局帳號不可為空'),
            3 => array('key' => 'not null', 'msg' => '銀行/郵局戶名不可為空'),

        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if(!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }


        $this->db->trans_begin();
        if($this->Model->checkSalary() > 0){
            $update = array(
                'rs_name' => $data['name'],
                'rs_code' => $data['code'],
                'rs_account' => $data['account'],
                'rs_accountName' => $data['account_name'],
                'rs_date' => date('Y-m-d H:i:s')
            );
            $this->Model->updateSalaryData($update);
        }else {
            $insert = array(
                't_id' => $_SESSION['Tid'],
                'rs_name' => $data['name'],
                'rs_code' => $data['code'],
                'rs_account' => $data['account'],
                'rs_accountName' => $data['account_name'],
                'rs_date' => date('Y-m-d H:i:s')
            );
            $this->Model->addSalaryData($insert);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '更新匯款資料失敗'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '更新匯款資料成功'));
        }
    }
}