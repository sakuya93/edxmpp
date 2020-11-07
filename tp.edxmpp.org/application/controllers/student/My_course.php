<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_course extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("my_course_model", "Model", TRUE);
    }

    public function index($page = 1)
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();
            //初始化變數
            $dataArray = "";
            $pageCount = $this->Model->getMyCourseCount() ; //頁數總數
            if($pageCount % 10 == 0)
                $pageCount = (int)($pageCount/10)-1;
            else
                $pageCount = (int)($pageCount/10);
            $pageCount += 1;

            $HTML = array(
                'content' => '',
                'name' => '',
                'photo_path' => '',
                'Events' => array(),
                'noEvents' => array(),
                'pageContent' => ''
            );
            //初始化變數結束


            ///////////////////////////    形成頁面轉換 start
            if ($page - 1 == 0) {
                $HTML['pageContent'] .= "<a class=\"btn fa fa-arrow-left\" disabled>上一頁</a>";
            } else {
                $temp = $page - 1;
                $HTML['pageContent'] .= "<a class=\"btn fa fa-arrow-left\" href=\"../my_course/{$temp}\">上一頁</a>";
            }

            for ($i = 1; $i <= $pageCount; $i++) {
                if ($i == $page) {
                    $HTML['pageContent'] .= "<a class=\"btn btn-primary\" href=\"../my_course/{$page}\">{$page}</a>";
                } else {
                    $HTML['pageContent'] .= "<a class=\"btn btn-secondary\" href=\"../my_course/{$i}\">{$i}</a>";
                }
            }

                //判斷有沒有下一頁
            if ($page + 1 > $pageCount) {
                $HTML['pageContent'] .= "<a class=\"btn fa fa-arrow-right\" disabled><span style=\"float:left\">下一頁</span></a>";
            } else {
                 $temp = $page + 1;
                $HTML['pageContent'] .= "<a class=\"btn fa fa-arrow-right\" href=\"../my_course/{$temp}\"><span style=\"float:left\">下一頁</span></a>";
            }
            ///////////////////////////    形成頁面轉換 End

            if (!$this->check_memberData()) {
                $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                $HTML['course_management_link'] = '<a class="nav-link" href="../modify_member_information">帳號設定</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../become_teacher">修改老師資料</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="../course_management/index/type_live_course">課程管理</a>';
            }

            $data = $this->Model->getMyCourse($page);

            if ($data == null) {
                //形成ERROR HTML
                $HTML['content'] .= "<h5 style='margin-top: 10px;padding: 10px;font-size: 24px;color: grey'>目前尚無我的課程</h5>";
            } else {

                for ($i = 0; $i < count($data); $i++) {
                    //抓每一筆課程的匹配時間
                    if ($data[$i]->l_id == null) {
                        $type = '影片';
                        $filmData = $this->Model->getFilmData($data[$i]->cf_id);
                        $data[$i]->type = @$filmData->cf_type;  //這邊@是為了防止找不到cf_type而發生錯誤訊息
                        $url = "../film_courses/{$data[$i]->cf_id}?c=TWD";
                    } else {
                        $type = '直播';
                        $url = "../Teacher_sales/live/{$data[$i]->l_id}?c=TWD";
                    }
                    if($data[$i]->l_id != null) {
                        $data_matchTime = $this->Model->getMatchTime($data[$i]->l_id);
                        if ($data_matchTime != null) {
                            foreach ($data_matchTime as $index => $tempData) {
                                if($i > 10)
                                    break;
                                $HTML['Events'][$i][$index]['id'] = $tempData->lt_id;
                                $HTML['Events'][$i][$index]['id2'] = $data[$i]->sc_id;
                                $HTML['Events'][$i][$index]['id3'] = $data[$i]->l_id;
                                $HTML['Events'][$i][$index]['amount'] = $tempData->lt_lastPeople;
                                $HTML['Events'][$i][$index]['note'] = $tempData->note;


                                if ($this->Model->getWhetherMatchTime($tempData->lt_id) > 0) {
                                    if (!is_null($tempData->note)) {
                                        $HTML['Events'][$i][$index]['title'] = substr($tempData->lt_time, 11) . "\n備註:" . substr($tempData->note, 0, 10) . "\n已匹配";
                                    } else {
                                        $HTML['Events'][$i][$index]['title'] = substr($tempData->lt_time, 11) . "\n備註:目前課程無簡介\n已匹配";
                                    }
                                    $HTML['Events'][$i][$index]['color'] = "#EA9086"; // aaffaa
                                } else {
                                    if (!is_null($tempData->note)) {
                                        $HTML['Events'][$i][$index]['title'] = substr($tempData->lt_time, 11) . "\n 剩餘: {$tempData->lt_lastPeople}人" . "\n備註:" . substr($tempData->note, 0, 10);
                                    } else {
                                        $HTML['Events'][$i][$index]['title'] = substr($tempData->lt_time, 11) . "\n 剩餘: {$tempData->lt_lastPeople}人" . "\n備註:目前課程無簡介";
                                    }
                                }
                                $HTML['Events'][$i][$index]['start'] = substr($tempData->lt_time, 0, 10);
                            }
                        } else {
                            $HTML['noEvents'][$i] = "<h3 style='margin-top: 10px;padding: 10px;font-size: 24px;color: grey'>目前此課程尚無匹配時間可以瀏覽，請洽詢上課老師或寄信回報！</h3>";
                        }
                    }else{
                        $HTML['noEvents'][$i] = "<h3 style='margin-top: 10px;padding: 10px;font-size: 24px;color: grey'>目前此課程尚無匹配時間可以瀏覽，請洽詢上課老師或寄信回報！</h3>";
                    }

                    $total_price = $data[$i]->price * $data[$i]->sc_NumberOfLessons; //總價
                    $data[$i]->photo .= "?value=" . uniqid();
                    $data[$i]->sc_NumberOfLessons = $data[$i]->sc_NumberOfLessons == -1 ? "<br>固定上課時間課程" : "共" . $data[$i]->sc_NumberOfLessons . "堂";

                    if($data[$i]->cf_id == NULL){ //直播課程
                        $course_photo = "live/{$data[$i]->l_thumbnail}";
                        $type = "直播";
                    }
                    else if($data[$i]->cf_id != NULL){ //影片課程
                        $course_photo = "film/{$data[$i]->cf_thumbnail}";
                        $type = "影片";
                    }
                    $HTML['content'] .= "
                    <div class=\"row course_block\">
                    
                        <div class=\"col-sm-4 course_info\">
                            <div class=\"col-sm-12\">
                                <a href=\"$url\" target=\"_blank\"><img class=\"sticker col-sm-4\"
                                    src=\"../resource/image/teacher/{$course_photo}\"></a>
                            </div>
                            <div class=\"col-sm-12\">
                                <b><span class=\"name\">{$data[$i]->sc_className}</span></b><br>
                                <span id=\"teach\">{$data[$i]->t_name}</span><br>
                                <b>來自</b> <span id=\"from\">{$data[$i]->country}</span> <br>
                                <b>會說</b> <span id=\"speak\">{$data[$i]->speakLanguage}</span>
                            </div>
                        </div>
                        
                        <div class=\"course_filed_mobile mb-10\">
                            <p class=\"title_text col-sm-3\">形式</p>
                            <p class=\"title_text col-sm-3\">類別</p>
                            <p class=\"title_text col-sm-3\">時/堂數</p>
                            <p class=\"title_text col-sm-3\">工具</p>
                        </div>
        
                        <div class=\"row col-sm-8 course_detail\">
                            <div class=\"col-sm-3\">
                                <p>$type</p>
                            </div>
        
                            <div class=\"col-sm-3\">
                                <p>{$data[$i]->type}</p>
                            </div>
        
                            <div class=\"col-sm-4\">
                                <p>{$data[$i]->hours}H/堂, {$data[$i]->sc_NumberOfLessons}</p>
                            </div>
                            
                            <div class=\"col-sm-2 tools\">
                                <i class=\"fa fa-plus\" onclick=\"openCollapse({$i}, 1)\" id=\"open_{$i}\" data-toggle=\"collapse\" href=\"#collapseExample_{$i}\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\"></i>
                            </div>
                        </div>
                    </div>
                    <div class=\"collapse\" id=\"collapseExample_{$i}\">
                        <div class=\"card card-body\">
                            <div id=\"calendar_{$i}\" class=\"calendar_body\"></div>
                        </div>
                    </div>
                    ";
                }
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../');
            $this->load->view('student/my_course_view', $HTML);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function addStudentMatchTime()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $now = date("Y-m-d H:i") . ":00";
        $lt_time = substr($this->Model->getMatchTimeOne($data['id']), 0, 16) . ":00";
        $lt_time = str_replace("_", " ", $lt_time);
        $time = (strtotime($lt_time) - strtotime($now)) / (60);
        if ($time <= 10) {
            echo json_encode(array('status' => false, 'msg' => '只能在上課前10分鐘之前才能匹配，請確認後再操作'));
            return;
        }

        $insert = array(
            'lt_id' => $data['id'],
            'm_id' => $_SESSION['Mid'],
        );
        if ($this->Model->checkClassLastPeople($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '此時段上課人數已滿，請挑選其他時間'));
            return;
        }

        if (!$this->Model->checkStudentMatchTime($insert)) {
            if ($this->Model->checkClassLastHours($data['id2'])) {
                if ($this->Model->addStudentMatchTime($insert, $data['id2'])) {
                    echo json_encode(array('status' => true, 'msg' => '匹配上課時間成功，請準時上課以免影響您的上課時數'));
                    return;
                }
            } else
                echo json_encode(array('status' => false, 'msg' => "親愛的會員您這堂課已經全數上完了，無法進行匹配上課時間，如想繼續上此課請重新購買此課程。\n<input type='button' class='btn btn-info' onclick=\"window.location='../Teacher_sales/{$data['id3']}'\" value='前往購買課程'>"));
        } else {
            echo json_encode(array('status' => false, 'msg' => '此課程的此時段以匹配過請挑選其他時段'));
        }
    }

    public function deleteStudentMatchTime()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            1 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
            2 => array('key' => 'not null', 'msg' => '資料不完整，請刷新頁面重新嘗試'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $insert = array(
            'lt_id' => $data['id'],
            'm_id' => $_SESSION['Mid'],
        );
        echo $this->Model->deleteStudentMatchTime($insert, $data['id2']);
    }
}

