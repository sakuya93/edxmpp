<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("dashboard_model", "Model", TRUE);
    }

    public function index($id = '', $page = 1)
    {
        $data = new stdClass();

        $bo = $this->getlogin();
        if ($bo) {
            $this->checkOneLogin();
            if($this->Model->checkMember($id)){
                redirect(base_url('student'));
            }
            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            $data->classData = $this->Model->getClassData($id);          //<-取得直播評語
            $data->filmData = $this->Model->getFilmData($id);             //<-取得擁有影片課程
            $data->memberData = $this->Model->getMemberData($id);         //<-會員基本資料
            if(isset($data->memberData[0]->teacherID))
                $data->memberData[0]->identity = '學員、老師';
            else
                $data->memberData[0]->identity = '學員';
            $data->memberData[0]->photo .= "?v=" . uniqid();
//            var_dump($data->memberData);
//            return;


            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data->course_management_link = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data->course_management_link = '<a class="nav-link" href="../../modify_member_information">帳號設定</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $data->course_management_link = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data->name = $name != '' ? $name : 'XXX';
            $data->photo_path = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';

//            $data->classOption = $this->getClassOption('../../');
            $data-> RightInformationColumn = $this->getRightInformationColumn('../../', $data->photo_path, $data->name);

            $data->headerRightBar = $this->getHeaderRightBar('../../', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('../../');

            $data->isLogin = $bo;

            $this->load->view('student/dashboard_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/share/report_user_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function checkType($tempType)
    {
        $data = $this->Model->getClassOptionKey($tempType);
        return $data->option;
    }

    public function MemberReport(){
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '檢舉選項不可為空'),
            3 => array('key' => '', 'msg' => ''),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if($data['reported'] == $_SESSION['Mid']){
            echo json_encode(array('status' => false, 'msg' => '無法檢舉自己'));
            return;
        }
        $this->load->library('my_report');
        echo $this->my_report->addMemberReport($data);
    }
}
