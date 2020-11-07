<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherCheck_detail extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_TeacherCheck_detail_model", "Model", TRUE);
    }

    public function index($id = '')
    {
        if (!$this->getlogin())
            redirect(base_url('admin_management'));
        if ($this->Model->checkTeacherIsNull($id) != 1)
            redirect(base_url('admin_management'));
//        if ($this->getlogin()) {
//            $data = $this->Form_security_processing($this->input->post());
        $result = $this->Model->getDetailedCheck($id);

        $HTML = array(
            'work_content' => '',
            'education_content' => '',
            'teaching_content' => '',
            'id' => $id
        );

        //基本資料
        $HTML['photo'] = $result->singelData->photo == null ? "noPhoto.jpg" : $result->singelData->photo;
        $HTML['name'] = $result->singelData->name;
        $HTML['country'] = $result->singelData->country;
        $HTML['speakLanguage'] = $result->singelData->speakLanguage;

        //老師介紹
        $HTML['veryShort_des'] = $result->singelData->veryShort_des;
        $HTML['short_des'] = $result->singelData->short_des;
        $HTML['des'] = $result->singelData->des;

        //工作經驗
        $work = $result->work;
        if ($work == null) {
            $HTML['work_content'] = "<h5 style='margin-left: 10px;color: grey'>無工作經驗</h5>";
        } else {
            foreach ($work as $key => $value) {
                if ($key >= 1) {
                    $HTML['work_content'] .= "<br><hr><br>";
                }
                $HTML['work_content'] .= "<div class=\"mls-15\">
            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">開始日期</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->start_date}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">結束日期</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->start_date}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">單位名稱</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->company_name}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">服務內容</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->service_content}\" readonly>
            </div>
        </div>";
            }
        }

        //學歷背景
        $education = $result->education;

        if ($education == null) {
            $HTML['education_content'] = "<h5 style='margin-left: 10px;color: grey'>無學歷背景</h5>";
        } else {
            foreach ($education as $key => $value) {
                if ($key >= 1) {
                    $HTML['education_content'] .= "<br><hr><br>";
                }
                $HTML['education_content'] .= "<div class=\"mls-15\">
            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">開始日期</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->start_date}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">結束日期</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->end_date}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">學校名稱</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->school_name}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">科系名稱</div>
                <input type=\"text\" class=\"form-control\" value=\"{$value->department_name}\" readonly>
            </div>

            <div class=\"input-group col-sm-6 mb-3\">
                <div class=\"input-title col-sm-12\">證明文件</div>
                <img src=\"../resource/image/student/education_prove/{$value->certified_documents}?value=" . uniqid() . "\" alt=\"\"
                     width=\"170\" height=\"160\">
            </div>
        </div>";
            }
        }

        //教學證照
        $teaching = $result->teaching;

        if ($teaching == null) {
            $HTML['teaching_content'] = "<h5 style='margin-left: 10px;color: grey'>無教學證照</h5>";
        } else {
            foreach ($teaching as $key => $value) {
                $HTML['teaching_content'] .= "<div class=\"prove_area\">
                    <span class=\"prove_title\">證明名稱: </span>
                    <span class=\"prove_name\">{$value->license_name}</span>
                    <br>
                    <img src=\"../resource/image/student/teaching_license/{$value->file}?value=" . uniqid() . "\"
                         alt=\"\" class=\"certified_documents_img\" width=\"165\" height=\"150\"></div>";
            }
        }

        $this->load->view('admin/TeacherCheck_detail_view', $HTML);
        $this->load->view('window/hint_window');
//        } else
//            redirect(base_url('home'));
    }
}
