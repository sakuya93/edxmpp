<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_courses extends label
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("live_courses_model", "Model", TRUE);
    }
    public function checkModifyPermissions($l_id = ''){
        if($this->db->select('*')->from('live')->where('l_id', $l_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 1)
            return false;
        return true;
    }

    public function index()
    {
        if (isset($_SESSION['Tid'])) {
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;

            $data['name'] = $name != '' ? $name : 'XXX';
            $data['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $data['classOption'] = $this->getClassOption();
            $data['RightInformationColumn'] = $this->getRightInformationColumn('', $data['photo_path'], $data['name']);

            $data['headerRightBar'] = $this->getHeaderRightBar('', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
            $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');

            $option = $this->Model->getOption();
            $data['option'] = "";
            foreach($option as $temp)
                $data['option'] .= "<option value=\"{$temp->option}\">{$temp->option}</option>";

            $this->load->view('teacher/live_courses_view', $data);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function add_courses()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            0 => array('key' => 'not null', 'msg' => '請填寫直播名稱'),
            1 => array('key' => 'not null', 'msg' => '請填寫體驗影片'),
            2 => array('key' => 'not null', 'msg' => '請填寫課程類型'),
            3 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            5 => array('key' => '^\d+$', 'msg' => '請填寫課程時數'),
            6 => array('key' => '^\d+$', 'msg' => '上課程數只能填寫數字'),
            7 => array('key' => '^\d+$', 'msg' => '上課人數只能填寫數字'),
            8 => array('key' => 'not null', 'msg' => '請選擇課程標籤'),
        );
        if($data['classMode'] != '0' & $data['classMode'] != '1'){
            echo json_encode(array('status' => false, 'msg' => '請勿修改程式碼'));
            return;
        }

        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            if ($this->Model->check_courses($data['actualMovie'])) {
                $uuid = uniqid();

                $config['upload_path'] = "resource/image/teacher/live/";
                $config['allowed_types'] = 'jpg|png';
                $config['file_name'] = $uuid;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('thumbnail')) {
                    $error = array('error' => $this->upload->display_errors());
                    echo json_encode(array('status' => false, 'msg' => "新增圖片失敗: " . $error));
                    return false;
                } else {
                    $image = $this->upload->data();
                }
                $label = "";
                $labelArray = explode(",", $data['label']);
                foreach ($labelArray as $temp) {
                    if($this->Model->checkLabelIsNull($temp) != 1){
                        echo json_encode(array('status' => false, 'msg' => '新增標籤中有無規定的標籤，請勿嘗試修改'));
                        return;
                    }
                    if (strpos(mb_convert_encoding($label, 'utf-8'), mb_convert_encoding($temp, 'utf-8')) === false)
                        $label .= "{$temp}、";
                }

                $insert = array(
                    't_id' => $_SESSION['Tid'],
                    'l_id' => $uuid,
                    'l_actualMovie' => $data['actualMovie'],
                    'l_experienceFilm' => $data['experienceFilm'],
                    'l_type' => $data['type'],
                    'l_thumbnail' => $image['orig_name'],
                    'l_introduction' => $data['introduction'],
                    'l_briefIntroduction' => $data['brief_introduction'],
                    'l_hours' => $data['hours'],
                    'l_numberPeople' => $data['numberPeople'],
                    'l_label' => $label,
                    'l_classMode' => $data['classMode']
                );
                if ($this->Model->add_courses($insert)) {
                    echo json_encode(array('status' => true, 'msg' => '新增課程成功'));
                } else
                    echo json_encode(array('status' => false, 'msg' => '新增課程失敗'));
            } else {
                echo json_encode(array('status' => false, 'msg' => '直播名稱重複請更改直播名稱'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
        }
    }

    public function delete_courses()
    {
//        $this->checkOneLogin();
//        $data = $this->Form_security_processing($this->input->post());
//        if ($this->Model->delete_courses($data['actualMovie']))
//            echo json_encode(array('status' => true, 'msg' => '課程刪除成功'));
//        else
//            echo json_encode(array('status' => false, 'msg' => '刪除課程失敗，請重新嘗試'));
    }

    public function edit_courses($tempData = '')
    {
        //初始化變數
        $HTML = array(
            'content' => '',
        );

        if (!$this->check_memberData()) {
            $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
        } else {
            $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
        }

        if (!$this->check_teacherData()) {
            redirect(base_url('student'));
        } else {
            $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
            $HTML['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
        }

        $data = $this->Form_security_processing(array('l_id' => $tempData));
        if (!$this->Model->checkCourses($data['l_id']))
            redirect(base_url('course_management/index/type_live_course'));

        $dataArray = $this->Model->search_courses($data['l_id']);
        $image_path = '../../resource/image/teacher/live/' . $dataArray->thumbnail . "?value=" . uniqid();

        if ($this->getlogin()) {
            $this->checkOneLogin();

            $option = $this->Model->getOption();
            $option_content = "";
            foreach($option as $temp)
                $option_content .= "<option value=\"{$temp->option}\">{$temp->option}</option>";

            $HTML['content'] .= "
                <div class=\"input-group col-sm-6 mb-3\">
                     <form id=\"update_thumbnail_form\" enctype=\"multipart/form-data\" method=\"post\">
                           <div class=\"input-title col-sm-12\">課程縮圖<br> <img src=\"{$image_path}\" data[0]=\"{$dataArray->thumbnail}\" width=\"160\" height=\"160\" id=\"thumbnail\"></div>
                        <input type=\"file\" id=\"photo\" onchange=\"handle(this.files, 'thumbnail'), $('#upload_image_btn').attr('disabled', false);\" name=\"photo\" accept=\"image/png, image/jpeg\">
                        <button type=\"submit\" class=\"btn btn-primary\" id=\"upload_image_btn\" disabled=\"\">更新課程縮圖</button>
                     </form>
                </div>
                
                <form id=\"live_courses_form\" enctype=\"multipart/form-data\" method=\"post\">
                <div class=\"input-group col-sm-6 mb-3 dy-none\">
                    <input type=\"text\" class=\"form-control live_courses-data\" value=\"{$dataArray->id}\">
                </div>

                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">直播名稱<span class=\"error-text basic-data-error\">直播名稱不可為空</span></div>
                    <input type=\"text\" class=\"form-control live_courses-data\" value=\"{$dataArray->actualMovie}\">
                </div>
                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12 dy-inline\">體驗影片(請輸入watch?v=後面的網址即可)
                        <button class=\"btn fa fa-info-circle\" id=\"video_info\" onclick=\"video_info_open();\" type=\"button\"
                                data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"體驗影片網址提示\"></button>
                                <br>
                        <span class=\"error-text basic-data-error\">體驗影片網址不可為空</span>
                    </div>
                    <input type=\"text\" class=\"form-control live_courses-data\" id=\"video_url_input\" value=\"{$dataArray->experienceFilm}\">
                    <iframe width=\"560\" height=\"315\" src=\"\" id=\"video\" class=\"mt-10\" frameborder=\"0\"
                            allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" data[0]=\"{$dataArray->experienceFilm}\"
                            allowfullscreen></iframe>
                </div>
                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">課程類型<span class=\"error-text basic-data-error\">請選擇課程類型</span></div>
                    <select class=\"custom-select live_courses-data\" id=\"type\" data[0]=\"{$dataArray->type}\">
                        <option value=\"\" selected=\"\" disabled=\"\">請選擇類型</option>
                        {$option_content}
                    </select>
                </div>
                
   
      
                

                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">課程介紹<span class=\"error-text basic-data-error\">課程介紹不可為空</span></div>
                    <textarea name=\"editor1\" id=\"editor1\" rows=\"10\" class=\"form-control\" cols=\"80\"></textarea>
                </div>
                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">課程簡介<span class=\"error-text basic-data-error\">課程介紹不可為空</span></div>
                    <textarea name=\"editor2\" id=\"editor2\" rows=\"10\" class=\"form-control\" cols=\"80\"></textarea>
                </div>
                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">課程時數(以小時為單位)<span class=\"error-text basic-data-error\">課程時數不可為空或輸入數字以外的字符</span></div>
                    <input type=\"text\" class=\"form-control live_courses-data\" value=\"{$dataArray->hours}\">
                </div>
                <div class=\"input-group col-sm-6 mb-3\">
                    <div class=\"input-title col-sm-12\">上課人數<span class=\"error-text basic-data-error\">上課人數不可為空或輸入數字以外的字符</span></div>
                    <input type=\"number\" class=\"form-control live_courses-data\" value=\"{$dataArray->numberPeople}\">
                </div>
               

                <div class=\"col-sm-6\">
                    <input class=\"btn btn-primary mb-3\" type=\"submit\" value=\"儲存\">
                </div>
                </form>
                <div id=\"editorData1\" style=\"display: none\">$dataArray->introduction</div>
                <div id=\"editorData2\" style=\"display: none\">$dataArray->briefIntroduction</div>";


            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;

            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $HTML['classOption'] = $this->getClassOption("../../");
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../../', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('../../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

            $this->load->view('teacher/edit_course_view', $HTML);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else {
            redirect(base_url('home'));
        }
    }

    public function update_courses()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => '', 'msg' => ''),
            1 => array('key' => 'not null', 'msg' => '請填寫直播名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫體驗影片'),
            3 => array('key' => 'not null', 'msg' => '請填寫課程類型'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            5 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            6 => array('key' => '^\d+$', 'msg' => '請填寫課程時數'),
            7 => array('key' => '^\d+$', 'msg' => '上課人數只能填寫數字'),
        );

        $Form_normalization = $this->Form_normalization($data, $config);
        if(!$Form_normalization->type){
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        //檢查是否有重複課程名稱
        if ($this->Model->check_courses_name($data['l_id'], $data['actualMovie'])) {
            $update = array(
                'l_actualMovie' => $data['actualMovie'],
                'l_experienceFilm' => $data['experienceFilm'],
                'l_type' => $data['type'],
                'l_introduction' => $data['introduction'],
                'l_briefIntroduction' => $data['brief_introduction'],
                'l_hours' => $data['hours'],
                'l_numberPeople' => $data['numberPeople'],
            );
            if ($this->Model->update_courses($data['l_id'], $update))
                echo json_encode(array('status' => true, 'msg' => '修改課程成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '修改課程失敗'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '直播名稱重複，請改名字後再送出!'));
        }


    }

    public function update_thumbnail($id = '')
    {
        $this->checkOneLogin();
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($temp_data);

        $imagePath = "resource/image/teacher/live/";
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'jpg|png';
        $this->load->library('upload', $config);

        //更新圖片
        if ($this->upload->do_upload('thumbnail')) {
            $image = $this->upload->data();
            unlink("{$imagePath}{$data['thumbnail_id']}");
            rename("{$imagePath}{$image['orig_name']}", "{$imagePath}{$data['thumbnail_id']}");
            echo json_encode(array('status' => true, 'msg' => '更新縮圖成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '更新縮圖失敗!!'));
        }
    }

    public function getClassData(){
        $data = $this->input->post();
        if($this->checkModifyPermissions($data['id1'])){
            return;
        }
        $returnData['className'] = $this->Model->getClassName($data['id1']);
        $returnData['key'] = $this->Model->getTeacherTeamsApiKey();
        if($this->Model->checkNumberStudents($data['id2']) == 1)
            $returnData['studentList'] = $this->Model->getClassList($data['id2'], 0, $data['id1']);
        else
            $returnData['studentList'] = $this->Model->getClassList($data['id2'], $this->Model->getClassMode($data['id1']), $data['id1']);
        echo json_encode($returnData);
    }

    public function createClassRoom(){
        $this->checkOneLogin();

        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => '', 'msg' => ''),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if($this->Model->checkAuthority($data['id']) != 1){
            echo json_encode(array('status' => false, 'msg' => '您無權存取此課程，請刷新頁面重新嘗試'));
            return;
        }
        if($this->Model->checkTeamsCreateStatus($data['id']) == 1){
            echo json_encode(array('status' => false, 'msg' => '此時段已經創建Teams上課教室了，請勿重複操作'));
            return;
        }

        $classMode = $this->Model->getClassMode2($data['id']);
        if($classMode != 1) {
            if (!($this->Model->checkNumberStudents($data['id']) > 0)) {
                echo json_encode(array('status' => false, 'msg' => '沒有學生匹配此段時間，故無法創建Teams上課教室'));
                return;
            }
        }

        $now = date("Y-m-d H:i").":00";
        $lt_time = substr($this->Model->getMatchTime($data['id']),0, 16).":00";
        $lt_time = str_replace("_"," ",$lt_time);
        $time = (strtotime($lt_time) - strtotime($now))/ (60);
        if(!($time > 0 && $time < 10)) {
            echo json_encode(array('status' => false, 'msg' => '創建教室只能為匹配時間前9分鐘，請確認後再操作'));
            return;
        }

        $this->db->trans_begin();
        $this->Model->updateTeamsCreateStatus($data);

        $classData = $this->Model->getClassData($data);
        $returnData['subject'] = "{$classData->l_actualMovie}-{$classData->t_name}({$classData->lt_time})";
        $returnData['content'] = $classData->lt_note;
        $returnData['timeZone'] = "Asia/Taipei";
        $returnData['startTime'] = substr($classData->lt_time, 0, 10). "T". substr($classData->lt_time, 11, 5). ":00.0000000";
        $returnData['endTime'] = substr($classData->lt_time, 0, 10). "T". substr($classData->lt_time, 17, 5). ":00.0000000";
        //$returnData['list'] = $this->Model->getClassList($data['id2'], $this->Model->getClassMode($data['id1']), $data['id1']);
        if($this->Model->checkNumberStudents($data['id']) == 1)
            $returnData['list'] = $this->Model->getClassList2($data['id'], 0);
        else
            $returnData['list'] = $this->Model->getClassList2($data['id'], $classMode);


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode($returnData);
        }
    }

    public function getMatchTime(){
        $data = $this->input->post();
        echo $this->Model->getMatchTime($data['id']);
    }

    public function getExperienceClass(){
        $data = $this->Model->getExperienceClass();
        for($i = 0; $i < count($data); $i+=1){
            if($data[$i]->contactID != null)
                $data[$i]->contact = "contact_detail('1','學生', '{$data[$i]->contactID}', '{$data[$i]->memberName}', '{$data[$i]->memberID}', '1')";
            else
                $data[$i]->contact = "contact_detail('1','學生', '', '{$data[$i]->memberName}', '{$data[$i]->memberID}', '1')";
        }
        echo json_encode($data);
    }
}
