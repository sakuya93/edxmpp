<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modify_member_information extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("modify_member_information_model", "Model", TRUE);
    }

    public function index($type = true)
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();
            $dataArray = $this->Model->get_member_information();
            
            $data = array(
                'name' => $dataArray->m_name,
                'date' => $dataArray->m_date,
                'timezone' => $dataArray->m_timezone,
                'country' => $dataArray->m_country,
                'motherTongue' => $dataArray->m_motherTongue,
                'speakLanguage' => $dataArray->m_speakLanguage,
                'city' => $dataArray->m_city,
//                'phoneNumber' => $dataArray->m_phoneNumber,
                'TeamsAccount' => $dataArray->m_teamsAccount,
                'line' => $dataArray->m_line,
                'email' => $dataArray->m_email,
                'photoPath' => $dataArray->m_photo == null ? 'noPhoto.jpg' : $dataArray->m_photo . "?v=" . uniqid(),
//                'classOption' => $this->getClassOption()
            );

            if (!$this->check_memberData() | !$this->getEmailStatus()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data['course_management_link'] = '<a class="nav-link" href="modify_member_information">帳號設定</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $photo = $nav->photo;
            $data['photo_path'] = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';
            $data['RightInformationColumn'] = $this->getRightInformationColumn('', $data['photo_path'], $data['name']);

            $data['headerRightBar'] = $this->getHeaderRightBar('', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
            $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');


            $this->load->view('student/modify_member_information_view', $data);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function modify_member_information()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '姓名不可為空'),
            1 => array('key' => 'not null', 'msg' => '生日不可為空'),
            2 => array('key' => 'not null', 'msg' => '時區不可為空'),
            3 => array('key' => 'not null', 'msg' => '國籍不可為空'),
            4 => array('key' => 'not null', 'msg' => '母語不可為空'),
            5 => array('key' => 'not null', 'msg' => '會說語言不可為空'),
            6 => array('key' => 'not null', 'msg' => '城市不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            $data['email'] = $_SESSION['user_name'];
            $data = array(
                'm_name' => $data['name'],
                'm_date' => $data['date'],
                'm_timezone' => $data['timezone'],
                'm_country' => $data['country'],
                'm_motherTongue' => $data['motherTongue'],
                'm_speakLanguage' => $data['speakLanguage'],
                'm_city' => $data['city']
            );
			$this->db->trans_begin();
            $this->Model->modify_member_information($data);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo json_encode(array('status' => '修改失敗'));
			}
			else
			{
				$this->db->trans_commit();
				echo json_encode(array('status' => '修改成功'));
			}
        } else
            echo json_encode(array('status' => $Form_normalization->msg));
    }

    public function upload_image()
    {
        $this->checkOneLogin();
        $image_name = $this->Model->getMember_uuid($_SESSION['user_name']);
        $config['upload_path'] = "resource/image/student/photo/";
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = $image_name;
        $image_path = $config['upload_path'] . $image_name;
        if (file_exists($image_path . '.jpg')) unlink($image_path . '.jpg');
        if (file_exists($image_path . '.png')) unlink($image_path . '.png');
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            $image = $this->upload->data();
        }
        $this->Model->upload_image($image);
        redirect(base_url('modify_member_information'));
    }

    public function email_send()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('status' => false, 'msg' => 'Email格式錯誤'));
            return;
        }
        if ($this->Model->checkEmailPass()) {
            echo json_encode(array('status' => false, 'msg' => '已通過信箱驗證，請勿重複驗證'));
            return;
        }
        if (!$this->Model->checkMemberInformation()) {
            echo json_encode(array('status' => false, 'msg' => '請先完成基本會員資料再進行信箱認證'));
            return;
        }
        $url = $this->Model->emailRecord();

        $this->load->library('email');
        $this->email->set_mailtype("html");

        $this->email->from('tpmanager0732@tp.edxmpp.org', 'XXX 教學平台管理員');
        $this->email->to("{$data['email']}");

        $content = "
            <html>
            <head>
                <style>
                    .container{
                        display: block;
                        width: 100%;
                        padding-right: 15px;
                        padding-left: 15px;
                        margin-right: auto;
                        margin-left: auto;
                    }
                    .text-center{
                        text-align: center;
                        align-items: center;
                    }
                    .btn{
                        display: inline-block;
                        text-align: center;
                        white-space: nowrap;
                        vertical-align: middle;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                        border: 1px solid transparent;
                        padding: 0.375rem 0.75rem;
                        font-size: 1rem;
                        line-height: 1.5;
                        border-radius: 0.25rem;
                        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                    }
                    .btn-success{
                        color: #fff;
                        background-color: #28a745;
             
                        cursor: pointer;
                        border: unset;
                    }
                    .btn-success:hover {
                        color: #fff;
                        background-color: #218838;
                        border-color: #1e7e34;
                        cursor: pointer;
                    }

                    .btn-success:focus, .btn-success.focus {
                        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
                    }
                </style>
            </head>
            <body>
                <div class='container text-center'>
                     <h1>您好! 感謝您註冊 XXX 教學平台，您可以點以下網址進行信箱驗證開通帳號權限</h1>
                     <a id=\"pass\" href=\"{$url}\" class=\"btn btn-success text-center\">點擊進行驗證</a>
                     <div>$url</div>
                </div>
            </body>
            </html>
        ";
        $this->email->subject("XXX 教學平台電子信箱驗證");
        $this->email->message($content);
        
        

        if ($this->email->send()) {
            $this->Model->updateEmail($data['email']);
            echo json_encode(array('status' => true, 'msg' => '驗證信已寄出請在十分鐘內前往信箱確認'));
        } else{
            var_dump($this->email->print_debugger(array('headers')));
            echo json_encode(array('status' => false, 'msg' => '驗證信寄出失敗請重新嘗試'));
        }

    }

    public function emailPass($email = '')
    {
        $this->checkOneLogin();
        $dataArray = $this->Model->get_member_information();
        $data = array(
            'name' => $dataArray->m_name,
            'date' => $dataArray->m_date,
            'timezone' => $dataArray->m_timezone,
            'country' => $dataArray->m_country,
            'motherTongue' => $dataArray->m_motherTongue,
            'speakLanguage' => $dataArray->m_speakLanguage,
            'city' => $dataArray->m_city,
         //   'phoneNumber' => $dataArray->m_phoneNumber,
            'line' => $dataArray->m_line,
            'photoPath' => $dataArray->m_photo == null ? 'noPhoto.jpg' : $dataArray->m_photo . "?v=" . uniqid(),
            'content' => ""
        );
//        $data['classOption'] = $this->getClassOption("../../");
        $data['RightInformationColumn'] = $this->getRightInformationColumn('../../', $data['photoPath'], $data['name']);
        if (!$this->check_memberData()) {
            $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
        } else {
            $data['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
        }

        if (!$this->check_teacherData()) {
//            $data['course_management_link'] = '<a class="nav-link" onclick="undone_teacherData()">課程管理</a>';
            $data['course_management_link'] = '<a class="nav-link" href="../../modify_member_information">帳號設定</a>';
        } else {
            $data['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
            $data['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
        }


        if ($this->Model->emailPass($email)) {
            $data['content'] .= "<img src='../../resource/pics/share/passed.png' height='300'>";
            echo json_encode(array('status' => true, 'msg' => '已通過驗證'));
        } else {
            $data['content'] .= "<img src='../../resource/pics/share/error.png' height='300'>";
            echo json_encode(array('status' => false, 'msg' => '驗證失敗，超過驗證時間'));
        }
        $this->load->view('student/email_pass_view', $data);
        $this->load->view('window/share/notice_window');
        $this->load->view('window/hint_window');
    }

    public function change_password()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $check = $this->Model->check_password($data['old_password']);
        if ($check == true) {
            $config = array(
                0 => array('key' => '', 'msg' => ''),
                1 => array('key' => '[a-zA-Z][a-zA-Z0-9]{7,15}', 'msg' => '新密碼格式輸入錯誤，請重新輸入。'),
                2 => array('key' => '', 'msg' => '')
            );
            $Form_normalization = $this->Form_normalization($data, $config);
            if ($Form_normalization->type) {
                $this->Model->change_password($data['new_password']);
                echo json_encode(array('status' => true, 'msg' => '更改密碼成功'));
            } else {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => '舊密碼輸入錯誤'));
        }
    }

    public function updateTeamsAccount()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => 'Teams帳號不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->Model->updateTeamsAccount($data['teamsAccount']))
            echo json_encode(array('status' => true, 'msg' => 'Teams帳號設定成功'));
        else
            echo json_encode(array('status' => false, 'msg' => 'Teams帳號設定失敗'));
    }
}
