<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_page extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("teacher_page_model", "Model", TRUE);
    }

    public function index($id = '', $index = '')
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();
            if($this->Model->checkTeacherIsNull($id) != 1)
                redirect(base_url('student'));
            $data->teacherData = $this->Model->getTeacherData($id);   //取得老師資料
            $data->teacherData->photo .= '?v='. uniqid();
            $data->teacherData->date = $this->age($data->teacherData->date);
            $data->teacherData->identity = '老師';
//            var_dump($data->teacherData);
//            return;
            $data->courseData = new stdClass();
            $data->courseData->live = $this->Model->getLiveData($id); //直播課程
            $data->courseData->film = $this->Model->getFilmData($id); //影片課程
            for($i = 0; $i < count($data->courseData->live); ++$i){
                $data->courseData->live[$i]->photo .= '?v='. uniqid();
            }
            for($i = 0; $i < count($data->courseData->film); ++$i){
                $data->courseData->film[$i]->photo .= '?v='. uniqid();
            }
//            var_dump($data->courseData->film);
//            return;

            $data->courseEvaluation = $this->Model->getCourseEvaluation($id);//評語(包跨直播和影片)
//            var_dump($data->courseEvaluation);
//            return;

            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
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
            $data->RightInformationColumn = $this->getRightInformationColumn('../../', $data->photo_path, $data->name);

            $data->headerRightBar = $this->getHeaderRightBar('../../', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('../../');

            $this->load->view('teacher/teacher_page_view', $data);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function checkType($tempType)
    {
        $data = $this->Model->getClassOptionKey($tempType);
        return $data->option;
    }


    function age($birthday)
    {
        if (strtotime($birthday) > 0) {
            return (int)((time() - strtotime($birthday)) / (86400 * 365)) . '歲';
        } else {
            return '-';
        }
    }

}
