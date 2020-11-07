<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Film_course extends label
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("add_film_course_model", "Model", TRUE);
    }

    public function index($id = '')
    {
        if (isset($_SESSION['Tid'])) {
            $this->checkOneLogin();
            if (!$this->check_memberData()) {
                $data['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $data['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">修改老師資料</a>';
                $data['course_management_link'] = '<a class="nav-link" href="../course_management/index/type_live_course">課程管理</a>';
            }
            $option = $this->Model->getOption();
            $data['option'] = "";
            foreach ($option as $temp)
                $data['option'] .= "<option value=\"{$temp->option}\">{$temp->option}</option>";
            if ($id != '') {
                if (!$this->Model->checkEditFilm($id))
                    redirect(base_url('course_management/index/type_film_course'));
                $data['basicInformation'] = $this->Model->get_coursesBasicInformation($id);
                $actualMovie = $this->Model->get_actualMovie($id);
                $data['actualMovie'] = "";
                for ($i = 1; $i < count($actualMovie); ++$i) {
                    $data['actualMovie'] .= "<div class=\"mtr-3 content\" id=\"film_content{$i}\">" .
                        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_film({$i})\"></i>" .
                        "<div class=\"col-sm-6 mb-3\">" .
                        "	<div class=\"series_search_title col-sm-12\">單元名稱</div>" .
                        "	<input type=\"text\" class=\"series_search_content col-sm-12 ml-1 actualMovie-data\" value=\"{$actualMovie[$i]->unitName}\">" .
                        "</div>" .
                        "<div class=\"input-group col-sm-6 mb-3\">" .
                        "<div class=\"input-title col-sm-12\">課程名稱:</div>" .
                        "<input type=\"text\" class=\"form-control actualMovie-data\" value=\"{$actualMovie[$i]->actualMovieName}\">" .
                        "</div>" .
                        "<div class=\"input-group col-sm-6 mb-3\">" .
                        "<div class=\"input-title col-sm-12 dy-inline\">影片網址(複製Youtube影片網址)" .
                        "<button class=\"btn fa fa-info-circle\" id=\"video_info\" onclick=\"video_info_open(" . "\'../\'" . ");\" type=\"button\"" .
                        " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"體驗影片網址提示\"></button>" .
                        "</div>" .
                        "<input type=\"text\" class=\"form-control actualMovie-data\" value=\"{$actualMovie[$i]->actualMovie}\">" .
                        "</div>" .
                        "</div>";
                }
                $data['actualMovieCount'] = count($actualMovie) - 1;
                $nav = $this->get_nav();
                $name = $nav->name;
                $photo = $nav->photo;
                $data['name'] = $name != '' ? $name : 'XXX';
                $data['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//                $data['classOption'] = $this->getClassOption("../");
                $data['RightInformationColumn'] = $this->getRightInformationColumn('../', $data['photo_path'], $data['name']);

                $data['headerRightBar'] = $this->getHeaderRightBar('../', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
                $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../');

                $this->load->view('teacher/edit_films_view', $data);
//                $this->load->view('window/student/collection_teacher_window');
                $this->load->view('window/share/notice_window');
                $this->load->view('window/hint_window');
            } else {
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
//                $data['classOption'] = $this->getClassOption("");
                $data['RightInformationColumn'] = $this->getRightInformationColumn('', $data['photo_path'], $data['name']);

                $data['headerRightBar'] = $this->getHeaderRightBar('', $data['photo_path'], $data['become_teacher_link'], $data['course_management_link']);
                $data['headerRightIconMenu'] = $this->getHeaderRightIconMenu('');

                $this->load->view('teacher/film_course_view', $data);
//                $this->load->view('window/student/collection_teacher_window');
                $this->load->view('window/share/notice_window');
                $this->load->view('window/hint_window');
            }

        } else
            redirect(base_url('home'));
    }

    public function add_coursesBasicInformation()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());

        $config = array(
            0 => array('key' => 'not null', 'msg' => '請填寫課程名稱'),
            1 => array('key' => 'not null', 'msg' => '請填寫影片介紹名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫介紹影片'),
            3 => array('key' => 'not null', 'msg' => '請填寫課程類型'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            5 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            6 => array('key' => '^\d+$', 'msg' => '課程時數只能填寫數字'),
            7 => array('key' => 'not null', 'msg' => '請選擇貨幣'),
            8 => array('key' => '^\d+$', 'msg' => '價格只能填寫數字'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            if ($this->Model->check_coursesBasicInformation($data['name'])) {
                $uuid = uniqid();

                $this->load->library('MY_currency');
                if ($this->my_currency->checkCurrency($data['currency'])) {
                    echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
                    return;
                }
                $config['upload_path'] = "resource/image/teacher/film/";
                $config['allowed_types'] = 'jpg|png';
                $config['file_name'] = $uuid;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('thumbnail')) {
                    echo json_encode(array('status' => false, 'msg' => "新增圖片失敗"));
                    return false;
                } else {
                    $image = $this->upload->data();
                }
                $label = "";
                $labelArray = explode(",", $data['label']);
                foreach ($labelArray as $temp) {
                    if (strpos(mb_convert_encoding($label, 'utf-8'), mb_convert_encoding($temp, 'utf-8')) === false)
                        $label .= "{$temp}、";
                }

                $insert = array(
                    't_id' => $_SESSION['Tid'],
                    'cf_id' => $uuid,
                    'cf_name' => $data['name'],
                    'cf_experienceFilm' => $data['experienceFilm'],
                    'cf_experienceFilmName' => $data['experienceFilmName'],
                    'cf_type' => $data['type'],
                    'cf_thumbnail' => $image['orig_name'],
                    'cf_introduction' => $data['introduction'],
                    'cf_briefIntroduction' => $data['brief_introduction'],
                    'cf_hours' => $data['hours'],
                    'cf_currency' => $data['currency'],
                    'cf_price' => $data['price'],
                    'cf_label' => $label
                );

                if ($this->Model->add_coursesBasicInformation($insert)) {
                    echo json_encode(array('status' => true, 'msg' => '新增課程成功', 'url' => $uuid));
                } else
                    echo json_encode(array('status' => false, 'msg' => '新增課程失敗'));
            } else {
                echo json_encode(array('status' => false, 'msg' => '課程名稱重複請更改課程名稱'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
        }
    }

    public function edit_coursesBasicInformation()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => '', 'msg' => ''),
            1 => array('key' => 'not null', 'msg' => '請填寫課程名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫影片介紹名稱'),
            3 => array('key' => 'not null', 'msg' => '請填寫介紹影片'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程類型'),
            5 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            6 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            7 => array('key' => '^\d+$', 'msg' => '課程時數只能填寫數字'),
            8 => array('key' => 'not null', 'msg' => '請選擇貨幣'),
            9 => array('key' => '^\d+$', 'msg' => '價格只能填寫數字'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if ($Form_normalization->type) {
            $this->load->library('MY_currency');
            if ($this->my_currency->checkCurrency($data['currency'])) {
                echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
                return;
            }
            $insert = array(
                't_id' => $_SESSION['Tid'],
                'cf_name' => $data['name'],
                'cf_experienceFilm' => $data['experienceFilm'],
                'cf_experienceFilmName' => $data['experienceFilmName'],
                'cf_type' => $data['type'],
                'cf_introduction' => $data['introduction'],
                'cf_briefIntroduction' => $data['brief_introduction'],
                'cf_hours' => $data['hours'],
                'cf_currency' => $data['currency'],
                'cf_price' => $data['price'],
            );
            if ($this->Model->edit_coursesBasicInformation($insert, $data['id'])) {
                echo json_encode(array('status' => true, 'msg' => '課程編輯成功'));
            } else
                echo json_encode(array('status' => false, 'msg' => '課程編輯失敗'));
        } else {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
        }
    }

    public function addCourseNotice()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => '', 'msg' => ''),
            1 => array('key' => 'not null', 'msg' => '請填寫課程名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫影片介紹名稱'),
            3 => array('key' => 'not null', 'msg' => '請填寫介紹影片'),
            4 => array('key' => 'not null', 'msg' => '請填寫課程類型'),
            5 => array('key' => 'not null', 'msg' => '請填寫課程介紹'),
            6 => array('key' => 'not null', 'msg' => '請填寫課程簡介'),
            7 => array('key' => '^\d+$', 'msg' => '課程時數只能填寫數字'),
            8 => array('key' => 'not null', 'msg' => '請選擇貨幣'),
            9 => array('key' => '\d.$', 'msg' => '價格只能填寫數字'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $insert = array(
            'nr_sendIdentity' => $_SESSION['Tid'],
            'nr_noticeObject' => '5',
            ''
        );
    }

    // 系列影片
    public function add_actualMovie()
    {
        $this->checkOneLogin();
        $data = array();

        $Data = $this->input->post();
        foreach ($Data as $key => $x) {
            if (!is_string($x))
                $data[] = $this->Form_security_processing($x);
            $id = $x;
        }

        $config = array(
            0 => array('key' => 'not null', 'msg' => '請填寫單元名稱'),
            1 => array('key' => 'not null', 'msg' => '請填寫影片名稱'),
            2 => array('key' => 'not null', 'msg' => '請填寫影片網址'),
        );

        $insert = array();
        $index = 0;
        foreach ($data as $tempData) {
            $Form_normalization = $this->Form_normalization($tempData, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            ++$index;
            $insertData = array(
                'cf_actualMovieIndex' => $index,
                't_id' => $_SESSION['Tid'],
                'cf_id' => $id,
                'cf_unitName' => $tempData['unitName'],
                'cf_actualMovieName' => $tempData['name'],
                'cf_actualMovie' => $tempData['film'],
            );
            $insert[] = $insertData;
        }

        if ($this->Model->add_actualMovie($insert)) {
            echo json_encode(array('status' => true, 'msg' => '更新系列影片成功'));
        } else
            echo json_encode(array('status' => false, 'msg' => '更新系列影片失敗'));
    }

    public function update_thumbnail()
    {
        $this->checkOneLogin();
        $data = $this->input->post();

        $imagePath = "resource/image/teacher/film/";
        $config['upload_path'] = $imagePath;
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);

        //更新圖片
        if ($this->upload->do_upload('thumbnail')) {
            $image = $this->upload->data();
            $ordImage = $this->Model->getOrdImage($data['id']);
            unlink("{$imagePath}{$ordImage->name}");
            rename("{$imagePath}{$image['orig_name']}", "{$imagePath}{$ordImage->name}");
            echo json_encode(array('status' => true, 'msg' => '更新縮圖成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '更新縮圖失敗!!'));
        }

    }

    public function delete_film_course()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        if ($this->Model->checkDeleteFilmCourse($data['cf_id']) > 0) {
            echo json_encode(array('status' => false, 'msg' => '已經有人購買此課程，所以無法刪除此課程'));
            return;
        }
        $imageName = $this->Model->getImageName($data['cf_id']);
        if (file_exists("resource/image/teacher/film/{$imageName}")) {
            unlink("resource/image/teacher/film/{$imageName}");
        } else {
            echo json_encode(array('刪除課程資料中途發生錯誤，請刷新後重新嘗試'));
            return;
        }
        if (file_exists("resource/image/teacher/film/{$imageName}")) {
            echo json_encode(array('刪除課程資料中途發生錯誤，請刷新後重新嘗試'));
            return;
        }
        if ($this->Model->delete_film_course($data['cf_id']))
            echo json_encode(array('status' => true, 'msg' => '刪除成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '刪除失敗'));
    }
}
