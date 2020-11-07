<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Become_teacher extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("become_teacher_model", "Model", TRUE);
    }

    public function index()
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();
            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            if (!$this->check_memberData()) {
                $data->become_teacher_link = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $data->course_management_link = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $data->course_management_link = '<a class="nav-link" href="modify_member_information">帳號設定</a>';
            } else {
                $data->become_teacher_link = '<a class="nav-link" href="become_teacher">修改老師資料</a>';
                $data->course_management_link = '<a class="nav-link" href="course_management/index/type_live_course">課程管理</a>';
            }

            $data->simple_data = $this->Model->getSimple_data();
            if ($data->simple_data == null) {
                $data->simple_data = new stdClass();
                $data->simple_data->name = " ";
                $data->simple_data->country = "";
                $data->simple_data->speak_language = "";
                $data->simple_data->very_short_des = "";
                $data->simple_data->short_des = "";
                $data->simple_data->des = "";
            } else {
                foreach ($data->simple_data as $key => $Data) {
                    if ($Data == null)
                        $data->simple_data->$key = '';
                }
            }
            $data->complex_data = new stdClass();
            $data->complex_data = $this->Model->getComplex_data();
            $data->edu_data = new stdClass();
            $data->edu_data = $this->Model->getEducation_data();
            $data->tl_data = new stdClass();
            $data->tl_data = $this->Model->get_teaching_license();

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $data->name = $name != '' ? $name : 'XXX';
            $data->photo_path = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';

//            $data->classOption = $this->getClassOption();
            $data->RightInformationColumn = $this->getRightInformationColumn('', $data->photo_path, $data->name);
            $data->headerRightBar = $this->getHeaderRightBar('', $data->photo_path, $data->become_teacher_link, $data->course_management_link);
            $data->headerRightIconMenu = $this->getHeaderRightIconMenu('');

            $this->load->view('student/become_teacher_view', $data);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function basic_information()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            0 => array('key' => 'not null', 'msg' => '姓名不可為空'),
//            1 => array('key' => 'not null', 'msg' => '性別不可為空'),
//            2 => array('key' => '^\d+$', 'msg' => '年齡只能為數字'),
            1 => array('key' => 'not null', 'msg' => '國籍不可為空'),
            2 => array('key' => 'not null', 'msg' => '會說語言不可為空'),

        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            if (!isset($_SESSION['Tid'])) {
                $insert = array(
                    't_id' => uniqid(),
                    't_name' => $data['name'],
                    't_speakLanguage' => $data['speak_language'],
                    't_country' => $data['country'],
//                    't_age' => $data['age'],
//                    't_sex' => $data['sex'],
					't_income' => 0
                );

                if ($this->Model->first_basic_information($insert)) {
                    $_SESSION['Tid'] = $insert['t_id'];
                    echo json_encode(array('status' => true, 'msg' => '老師基本資料更新成功'));
                    return;
                } else {
                    echo json_encode(array('status' => false, 'msg' => '老師基本資料更新失敗，請重新嘗試'));
                    return;
                }
            } else {
                $insert = array(
                    't_name' => $data['name'],
                    't_speakLanguage' => $data['speak_language'],
                    't_country' => $data['country'],
//                    't_age' => $data['age'],
//                    't_sex' => 'sex'
                );
                if ($this->Model->basic_information($insert))
                    echo json_encode(array('status' => true, 'msg' => '老師基本資料更新成功'));
                else
                    echo json_encode(array('status' => false, 'msg' => '老師基本資料更新失敗，請重新嘗試'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
        }
    }

    public function teacher_introduction()
    {
        $this->checkOneLogin();
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '請先完成老師基本資料，再填寫老師介紹'));
            return;
        }
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '極短描述不可為空'),
            1 => array('key' => 'not null', 'msg' => '簡短介紹不可為空'),
            2 => array('key' => 'not null', 'msg' => '詳細介紹不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            $update = array(
                't_veryShort_des' => $data['very_short_description'],
                't_short_des' => $data['short_description'],
                't_des' => $data['description']
            );
            if ($this->Model->teacher_introduction($update))
                echo json_encode(array('status' => true, 'msg' => '老師介紹資料更新成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '老師介紹資料更新失敗，請重新嘗試'));
        } else {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
        }
    }

    public function work_experience()
    {
        $this->checkOneLogin();
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '請先完成老師基本資料，再填寫老師介紹'));
            return;
        }
        $data = array();
        $Data = $this->input->post();

        if (count($Data) >= 10) {
            echo json_encode(array('status' => false, 'msg' => '工作經驗最多可新增10筆'));
            return;
        } else if (count($Data) == 0) {
            echo json_encode(array('status' => false, 'msg' => '請至少新增一筆工作經驗再進行儲存！'));
            return;
        }

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        $config = array(
            0 => array('key' => 'not null', 'msg' => '開始日期不可為空'),
            1 => array('key' => 'not null', 'msg' => '結束日期不可為空'),
            2 => array('key' => 'not null', 'msg' => '單位名稱不可為空'),
            3 => array('key' => 'not null', 'msg' => '服務內容不可為空')
        );
        $insert = array();

        foreach ($data as $checkData) {
            $Form_normalization = $this->Form_normalization($checkData, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            $checkData = array(
                't_id' => $_SESSION['Tid'],
                'w_id' => uniqid(),
                'w_start_date' => $checkData['start_date'],
                'w_end_date' => $checkData['end_date'],
                'w_company_name' => $checkData['company_name'],
                'w_service_content' => $checkData['service_content'],
            );
            $insert[] = $checkData;
        }
        if ($this->Model->work_experience($insert))
            echo json_encode(array('status' => true, 'msg' => '工作經驗資料更新成功'));
        else {
            $this->Model->delete_work_experience_data();
            echo json_encode(array('status' => false, 'msg' => '工作經驗資料更新失敗，請重新嘗試'));
        }
    }

    public function modifyWorkExperience()
    {
        $this->checkOneLogin();
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '請先完成老師基本資料，再填寫老師介紹'));
            return;
        }
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '開始日期不可為空'),
            1 => array('key' => 'not null', 'msg' => '結束日期不可為空'),
            2 => array('key' => 'not null', 'msg' => '單位名稱不可為空'),
            3 => array('key' => 'not null', 'msg' => '服務內容不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $update = array(
            'w_start_date' => $data['start_date'],
            'w_end_date' => $data['end_date'],
            'w_company_name' => $data['company_name'],
            'w_service_content' => $data['service_content'],
        );

        if ($this->Model->modifyWorkExperience($data['id'], $update))
            echo json_encode(array('status' => true, 'msg' => '工作經驗資料修改成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '工作經驗資料修改失敗'));

    }

    public function deleteWorkExperience()
    {
        $data = $this->input->post();
        if ($this->Model->deleteWorkExperience($data['id']))
            echo json_encode(array('status' => true, 'msg' => '刪除工作經驗成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '刪除工作經驗失敗'));
    }

    public function education_background()
    {
        $this->checkOneLogin();
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '請先完成老師基本資料，再填寫老師介紹'));
            return;
        }
        $temp_data = $this->input->post();

        if ($this->Model->education_background_count() + $temp_data['education_count'] > 10) {
            echo json_encode(array('status' => false, 'msg' => '學歷背景最多可新增10筆'));
            return;
        } else if ($temp_data['education_count'] == 0 & $this->Model->education_background_count() >= 1) {
            echo json_encode(array('status' => false, 'msg' => '若要修改學歷背景資訊請到對應的資訊上方點選修改按鈕!'));
            return;
        } else if ($temp_data['education_count'] == 0) {
            echo json_encode(array('status' => false, 'msg' => '請至少新增一筆學歷背景再進行儲存！'));
            return;
        }

        $data = $this->Form_security_processing($temp_data);

        $con = array(
            0 => array('key' => 'not null', 'msg' => '開始日期不可為空'),
            1 => array('key' => 'not null', 'msg' => '結束日期不可為空'),
            2 => array('key' => 'not null', 'msg' => '學校名稱不可為空'),
            3 => array('key' => 'not null', 'msg' => '科系名稱不可為空'),
            4 => array('key' => '', 'msg' => ''),
            5 => array('key' => '', 'msg' => '')

        );
        $insert = array();

        $config['upload_path'] = "resource/image/student/education_prove/";
        $config['allowed_types'] = 'jpg|png';
        $uuid = uniqid();
        $config['file_name'] = $uuid;
        $this->load->library('upload', $config);
        $num = -1;
        foreach ($data as $key => $checkData) {
            $num++;
            if ($num >= 6) $num = 0;
            $Form_normalization = $this->Form_normalization_one($checkData, $con[$num]);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
        }
        $jpg = 0;
        $png = 0;
        $imageName = '';
        for ($i = 0; $i < $data['education_count']; $i++) {
            if ($this->upload->do_upload("file{$i}")) {
                $image = $this->upload->data();
                $sub = mb_substr($image['orig_name'], -3);
                if ($sub == 'jpg') {
                    if ($jpg == 0)
                        $imageName = $image['orig_name'];
                    else
                        $imageName = str_replace('.', "{$jpg}.", $image['orig_name']);
                } else {
                    if ($png == 0)
                        $imageName = $image['orig_name'];
                    else
                        $imageName = str_replace('.', "{$png}.", $image['orig_name']);

                }

                $sub == 'jpg' ? $jpg++ : $png++;
                $id = uniqid();

                $checkData = array(
                    't_id' => $_SESSION['Tid'],
                    'ed_id' => $id,
                    'e_start_date' => $data["start_date{$i}"],
                    'e_end_date' => $data["end_date{$i}"],
                    'e_school_name' => $data["school_name{$i}"],
                    'e_department_name' => $data["department_name{$i}"],
                    'e_certified_documents' => $imageName,
                );
                $insert[] = $checkData;
            } else {
                echo json_encode(array('status' => false, 'msg' => '上傳失敗，請重新嘗試'));
                return;
            }
        }
		$this->db->trans_begin();
        if ($this->Model->education_background($insert)) {

        } else
            $this->delete_education_background_image($imageName);


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo json_encode(array('status' => false, 'msg' => '學歷背景資料更失敗，請重新嘗試'));
		}
		else
		{
			$this->db->trans_commit();
			echo json_encode(array('status' => true, 'msg' => '學歷背景資料更新成功'));
		}
    }

    public function delete_education_background_image($image = null)
    {
        $this->checkOneLogin();
        if (isset($image)) {
            $image_path = 'resource/image/student/education_prove/';
            if (file_exists("{$image_path}{$image}"))
                unlink("{$image_path}{$image}");
            elseif (file_exists("{$image_path}{$image}"))
                unlink("{$image_path}{$image}");
        }
    }

    public function edit_education_background_data()
    {
        $this->checkOneLogin();
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($this->input->post());

        $con = array(
            0 => array('key' => 'not null', 'msg' => '開始日期不可為空'),
            1 => array('key' => 'not null', 'msg' => '結束日期不可為空'),
            2 => array('key' => 'not null', 'msg' => '無法編輯此資料'),
            3 => array('key' => 'not null', 'msg' => '學校名稱不可為空'),
            4 => array('key' => 'not null', 'msg' => '科系名稱不可為空'),

        );

        $Form_normalization = $this->Form_normalization($data, $con);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $checkData = array(
            'e_start_date' => $data["start_date"],
            'e_end_date' => $data["end_date"],
            'e_school_name' => $data["school_name"],
            'e_department_name' => $data["department_name"],
        );

        if ($this->Model->edit_education_background($data["id"], $checkData)) {
            echo json_encode(array('status' => true, 'msg' => '編輯成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '編輯失敗，請重新嘗試'));
            return;
        }
    }

    public function edit_education_background_image($id = '')
    {
        $this->checkOneLogin();
        $imagePath = "resource/image/student/education_prove/";
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'jpg|png';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('certified_documents')) {
            $imageName = $this->Model->get_ed_imageName($id);
            $image = $this->upload->data();
            unlink("{$imagePath}{$imageName}");
            rename("{$imagePath}{$image['orig_name']}", "{$imagePath}{$imageName}");
        }
        redirect(base_url('become_teacher'));
    }

    public function delete_education_background()
    {
        $this->checkOneLogin();
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($this->input->post());;

        $con = array(
            0 => array('key' => 'not null', 'msg' => '無法編輯此資料'),
        );

        $Form_normalization = $this->Form_normalization($data, $con);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($this->Model->delete_education_background($data['id'])) {
            echo json_encode(array('status' => true, 'msg' => '刪除成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '刪除失敗，請重新嘗試'));
            return;
        }
    }

    public function teaching_license()
    {
        $this->checkOneLogin();
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '請先完成老師基本資料，再填寫老師介紹'));
            return;
        }
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($temp_data);

        if ($temp_data['teaching_license_count'] >= 10) {
            echo json_encode(array('status' => false, 'msg' => '教學證明最多可新增10筆'));
            return;
        } else if ($temp_data['teaching_license_count'] == 0 & $this->Model->teaching_license_count() >= 1) {
            echo json_encode(array('status' => false, 'msg' => '若要修改教學證照資訊請到對應的資訊上方點選修改按鈕!'));
            return;
        } else if ($temp_data['teaching_license_count'] == 0) {
            echo json_encode(array('status' => false, 'msg' => '請至少新增一筆教學證照再進行儲存！'));
            return;
        }

        $con = array(
            0 => array('key' => 'not null', 'msg' => '證明名稱不可為空'),
        );
        $insert = array();

        $config['upload_path'] = "resource/image/student/teaching_license/";
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);

        foreach ($data as $key => $checkData) {
            $Form_normalization = $this->Form_normalization_one($checkData, $con[0]);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
        }

        $png = 0;
        $jpg = 0;
        for ($i = 0; $i < $data['teaching_license_count']; $i++) {
            if ($this->upload->do_upload("file{$i}")) {
                $image = $this->upload->data();
                $sub = mb_substr($image['orig_name'], -3);
                if ($sub == 'jpg') {
                    if ($jpg == 0)
                        $imageName = $image['orig_name'];
                    else
                        $imageName = str_replace('.', "{$jpg}.", $image['orig_name']);
                } else {
                    if ($png == 0)
                        $imageName = $image['orig_name'];
                    else
                        $imageName = str_replace('.', "{$png}.", $image['orig_name']);

                }

                $sub == 'jpg' ? $jpg++ : $png++;
                $checkData = array(
                    't_id' => $_SESSION['Tid'],
                    'tl_id' => uniqid(),
                    'tl_license_name' => $data["fileName{$i}"],
                    'tl_file' => $imageName,
                );
                $insert[] = $checkData;
            }
        }
//            $image = $this->delete_education_background_image();
        if ($this->Model->teaching_license($insert)) {
//                $this->delete_education_background_image();
            echo json_encode(array('status' => true, 'msg' => '教學證明資料更新成功，審核通過將寄信通知'));
        } else {
//                $this->delete_education_background_image();
            echo json_encode(array('status' => false, 'msg' => '教學證明資料更新失敗，請重新嘗試'));
        }
    }

    public function delete_teaching_license()
    {
        $this->checkOneLogin();
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($temp_data);

        $con = array(
            0 => array('key' => 'not null', 'msg' => '無法編輯此資料'),
        );

        $Form_normalization = $this->Form_normalization($data, $con);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($image = $this->Model->delete_teaching_license_data($data['id'])) {
            $this->delete_teaching_license_image($image->file);

            echo json_encode(array('status' => true, 'msg' => '刪除成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '刪除失敗，請重新嘗試'));
            return;
        }
    }

    public function delete_teaching_license_image($image = null)
    {
        $this->checkOneLogin();
        if (isset($image)) {
            $image_path = 'resource/image/student/teaching_license/';
            if (file_exists("{$image_path}{$image}"))
                unlink("{$image_path}{$image}");
            elseif (file_exists("{$image_path}{$image}"))
                unlink("{$image_path}{$image}");
        }
    }

    public function edit_teaching_license_data()
    {
        $this->checkOneLogin();
        $temp_data = $this->input->post();

        $data = $this->Form_security_processing($temp_data);

        $con = array(
            0 => array('key' => 'not null', 'msg' => '證明名稱不可為空'),
        );

        $Form_normalization = $this->Form_normalization($data, $con);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $checkData = array(
            'tl_license_name' => $data["fileName"]
        );

        if ($this->Model->edit_teaching_license($data["id"], $checkData)) {
            echo json_encode(array('status' => true, 'msg' => '編輯成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '編輯失敗，請重新嘗試'));
            return;
        }
    }

    public function edit_teaching_license_image($id = '')
    {
        $this->checkOneLogin();
        $imagePath = "resource/image/student/teaching_license/";
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'jpg|png';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('teaching_license')) {
            $imageName = $this->Model->get_tl_imageName($id);
            $image = $this->upload->data();
            unlink("{$imagePath}{$imageName}");
            rename("{$imagePath}{$image['orig_name']}", "{$imagePath}{$imageName}");
        }
        redirect(base_url('become_teacher'));
    }
}

