<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_management extends label
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("course_management_model", "Model", TRUE);
    }

    public function index($type = "type_live_course")
    {
        if (isset($_SESSION['Tid'])) {
            $this->checkOneLogin();
            if (!$this->getEmailStatus())
                redirect(base_url('student'));
            // 初始化變數
            $HTML = array(
                'page_title' => '',
                'content' => '',
                'field' => '',
                'my_evaluation_button' => '',
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

            if ($type == "type_live_course") {
                $HTML['my_evaluation_button'] = "<button class=\"btn btn-dark fa fa-thumbs-o-up mt-10 tools-btn-mobile\" type=\"button\"
                                                onclick=\"window.location='../../my_course_evaluation/live/0'\">我的課程評價
                                                </button>";
                $HTML['release_button'] = "<div class=\"ml-10\"><button class=\"btn btn-success fa fa-cloud-upload mt-10 release_btn tools-btn-mobile\" type=\"button\"
                    onclick=\"update_release()\">更新發佈
                    </button></div>";
                $HTML['page_title'] = "課程管理頁面-直播";
                $HTML['give_evaluation_button'] = "<button class=\"btn btn-info fa fa-eyedropper mt-10 tools-btn-mobile\" type=\"button\"
                                        onclick=\"window.location='../../classStudent_information/type_live_course'\">給予學生評價
                                    </button>";
                $HTML['application_experience_button'] = "<button class=\"btn btn-secondary fa fa-graduation-cap mt-10 tools-btn-mobile\" type=\"button\"
                                        onclick=\"openExperienceApplicationList()\">體驗上課申請
                                    </button>";

                $dataArray = $this->Model->getCourses_data($_SESSION['Tid']);

                for ($i = 0; $i < count($dataArray); $i++) {
                    $release = ($dataArray[$i]->l_release == true) ? "checked" : ""; //先判斷是否有發佈

                    $thumbnail = $dataArray[$i]->l_thumbnail == NULL ? "../../resource/image/student/photo/noPhoto.jpg" : "../../resource/image/teacher/live/" . $dataArray[$i]->l_thumbnail;
                    $thumbnail .= "?value=" . uniqid();
                    if ($dataArray[$i]->l_classMode == 1)
                        $checkStartClass = 'background-color: rgba(51, 204, 204, 0.3);';
                    else
                        $checkStartClass = '';

                    $HTML['content'] .= "
            <div class=\"internal row\" id=\"internal{$i}\" style='{$checkStartClass}'>
                <div class=\"live_info col-sm-5\">
                    <div class=\"live_info_left col-sm-12\">
                        <input type=\"checkbox\" class=\"release\" id=\"check_{$i}\" {$release}>
                        <span class=\"course_type\">live</span>
                        <span class=\"course_id\" id=\"course_id{$i}\">{$dataArray[$i]->l_id}</span>
                        <a href=\"../../Teacher_sales/live/{$dataArray[$i]->l_id}?c=TWD\" target=\"_blank\"><img class=\"sticker col-sm-4\" src=\"{$thumbnail}\"></a>
                    </div>
                    
                    <div class=\"live_info_right col-sm-12\">
                        <div class=\"introduction\">
                            <b>課程名稱</b> <span id=\"teach\">{$dataArray[$i]->l_actualMovie}</span> <br>
                        </div>
                    </div>
                </div>


                <div class=\"row col-sm-12 live_filed_mobile\">
                    <div class=\"col-sm-3\">類別</div>
                    <div class=\"col-sm-3\">購買人數</div>
                    <div class=\"col-sm-2\">時數</div>
                    <div class=\"col-sm-2\">功能</div>
                    <div class=\"col-sm-2\">上課</div>
                </div>

                <div class=\"col-sm-8 live_detail\">
                    <div class=\"col-sm-2\">
                        <h5>{$dataArray[$i]->l_type}</h5>
                    </div>
    
                    <div class=\"col-sm-2 TA-center\">
                        <h5>{$dataArray[$i]->buyNumber}</h5>
                    </div>
    
                    <div class=\"col-sm-3 TA-center\">
                        <h5>{$dataArray[$i]->l_hours}</h5>
                    </div>
    
    
                    <div class=\"col-sm-3 tools\">
                        <i class=\"fa fa-ticket\" title=\"價格設定\" onclick=\"window.location = '../../course_management/court_discount/{$dataArray[$i]->l_id}'\"></i>
                        <i class=\"fa fa-clock-o\" title=\"匹配時間\" onclick=\"window.location = '../../course_management/match_time/{$dataArray[$i]->l_id}'\"></i>
                        <i class=\"fa fa-pencil-square\" title=\"編輯課程\" onclick=\"window.location = '../../live_courses/edit_courses/{$dataArray[$i]->l_id}' \"></i>
                    </div>
                    
                    <div class=\"col-sm-2 sel_class_status\">
                        <button type=\"button\" class=\"btn btn-primary\" id=\"start_class{$i}\" onclick=\"start_class('{$dataArray[$i]->l_id}','{$i}')\">開始上課</button>
                        <button type=\"button\" class=\"btn btn-danger\" id=\"course_notice{$i}\" onclick=\"course_notice('live','{$dataArray[$i]->l_id}')\">課程通知</button>
                    </div>
                </div>
            </div>";
                }
                $HTML['field'] .= "
                    <div class=\"col-sm-3\">
                        <p class=\"title_text col-sm-12\">課程資訊</p>
                    </div>
                    <div class=\"col-sm-9 row\">
                        <p class=\"title_text col-sm-3 mlr-0d5\">類別</p>
                        <p class=\"title_text col-sm-2\">購買人數</p>
                        <p class=\"title_text col-sm-2\">時數</p>
                        <p class=\"title_text col-sm-2\">功能</p>
                        <p class=\"title_text col-sm-2\">上課</p>
                    </div>
                ";
            } else if ($type == "type_film_course") {
                $HTML['my_evaluation_button'] = "<button class=\"btn btn-dark fa fa-thumbs-o-up mt-10 tools-btn-mobile\" type=\"button\"
                                                onclick=\"window.location='../../my_course_evaluation/courseFilm/0'\">我的課程評價
                                                </button>";
                $HTML['release_button'] = "<div class=\"ml-10\"><button class=\"btn btn-success fa fa-cloud-upload mt-10 release_btn tools-btn-mobile\" type=\"button\"
                    onclick=\"update_release()\">更新發佈
                    </button></div>";

                $HTML['page_title'] = "課程管理頁面-影片";

                $HTML['give_evaluation_button'] = "";
                $HTML['application_experience_button'] = "";

                $dataArray = $this->Model->getFilm_data($_SESSION['Tid']);

                $series_video = ""; //儲存系列影片HTML元素

                sort($dataArray);

                for ($i = 0, $j = 1, $count = 0; $i < count($dataArray); $i++, $j++) {
                    $uuid = uniqid(); //不讓圖片有緩存狀態的亂數

                    /*TODO start ---- 底下購買人數區必須改成正確的變數*/
                    $Buy_Number = $this->Model->getBuyNumber($dataArray[$i]->cf_id);
                    /*TODO end*/

                    if ($dataArray[$i]->cf_actualMovie != null) { //系列影片
                        $series_video .= "
                        <div class=\"series\">
                            <div class=\"col-sm\">
                                <div class=\"row\" style=\"flex-wrap: nowrap\">
                                    <i class=\"fa fa-play\"></i>
                                    <p class=\"series_course_name\">單元 {$j} 課程名稱:{$dataArray[$i]-> cf_actualMovieName}</p>
                                </div>
                            </div>
                            <div class=\"col-sm\">
                                <div class=\"series_course_content series_course_point\">重點: 這是此次課程的重點</div>
                            </div>
                            <div class=\"col-sm\">
                                <div class=\"series_course_content series_course_time\">影片長度: 1時 0分 12秒</div>
                            </div>
                            <div class=\"col-sm\">
                                <div class=\"row\">
                                    <i class=\"fa fa-file\"></i>
                                    <p class=\"series_course_url\">影片網址:
                                        <a href=\"https://www.youtube.com/watch?v={$dataArray[$i]-> cf_actualMovie}\"
                                           target=\"_blank\" style=\"font-weight: bold\"> 連結</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        ";
                    } else {
                        if ($series_video == "") { //如果無系列影片
                            $series_video = "
                            <div class=\"series\">
                                <p>目前暫無系列影片!</p>
                            </div>
                        ";
                        }

                        $release = ($dataArray[$i]->cf_release == true) ? "checked" : ""; //先判斷是否有發佈
                        $HTML['content'] .= "
                        <input type=\"checkbox\" class=\"release\" id=\"check_{$count}\" {$release}>
                        <div class=\"internal film_internal row\" id=\"internal_{$count}\" onclick=\"openCollapse({$count}, 1)\" href=\"#collapseExample_{$count}\"
                             data-toggle=\"collapse\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                            <div class=\"col-sm-2 film_info_left\">
                                
                                <span class=\"course_type\">film</span>
                                <span class=\"course_id\" id=\"course_id{$i}\">{$dataArray[$i]->cf_id}</span>
                                <a href=\"../../edit_film_course/{$dataArray[$i]-> cf_id}\" target=\"_blank\"><img class=\"sticker col-sm-4\"
                                                                                                    src=\"../../resource/image/teacher/film/{$dataArray[$i]-> cf_thumbnail}?value={$uuid}\"
                                                                                                    alt=\"無圖片\"
                                                                                                    style=\"min-width: 150px\"></a>
                            </div>
            
                            <div class=\"col-sm-4 film_info_right\">
                                <div class=\"col-sm\">
                                    <p class=\"name\"><b>課程名稱: {$dataArray[$i]-> cf_name}</b></p>
                                </div>
                            </div>
            
            
                            <div class=\"col-sm-12 film_mobile\">
                                <div class=\"col-sm-3\">類別</div>
                                <div class=\"col-sm-4\">購買人數</div>
                                <div class=\"col-sm-2 film_mobile_hours\">時數</div>
                                <div class=\"col-sm-3 film_mobile_series\">功能</div>
                            </div>
            
            
            
                            <div class=\"col-sm-6 film_detail\" style=\"display: flex;\">
                                <div class=\"col-sm-3 internal_form\">
                                    <h5>{$dataArray[$i]-> cf_type}</h5>
                                </div>
            
                                <div class=\"col-sm-4\" >
                                    <h5>{$Buy_Number[0]->buyNumber}</h5>
                                </div>
            
                                <div class=\"col-sm-2\">
                                    <h5>{$dataArray[$i]-> cf_hours}</h5>
                                </div>
            
                                <div class=\"col-sm-3 film_detail_series\">
                                    <i class=\"fa fa-bell\" title=\"課程通知\" onclick=\"course_notice('film','{$dataArray[$i]->cf_id}')\" ></i>
                                    <i class=\"fa fa-pencil-square\" title=\"編輯課程\" onclick=\"window.location ='../../edit_film_course/{$dataArray[$i]->cf_id}'\" ></i>
                                    <i class=\"fa fa-times-circle\" title=\"刪除課程\" onclick=\"delete_film_course('{$dataArray[$i] -> cf_id}')\"></i>
                                    <i class=\"fa fa-plus\" title=\"展開影片\" id=\"open_{$count}\"></i>
                                </div>
                            </div>
                        </div>
        
                        
                    <div class=\"collapse\" id=\"collapseExample_{$count}\">{$series_video}</div>
                ";
                        $j = 0; //將單元幾歸1
                        $count++; //切換到下一個課程
                        $series_video = ""; //將存放系列影片的變數清空
                    }
                }

                $HTML['field'] .= "
                    <p class=\"title_text title_info col-sm-6\">課程資訊</p>
                    <p class=\"title_text title_form col-sm\">類別</p>
                    <p class=\"title_text col-sm\">購買人數</p>
                    <p class=\"title_text col-sm\">時數</p>
                    <p class=\"title_text col-sm\">功能</p>
                ";
            } else if ($type == "type_fundraising_course") {
                $HTML['my_evaluation_button'] = "";
                $HTML['release_button'] = "";
                $HTML['page_title'] = "課程管理頁面-募資";

                $HTML['give_evaluation_button'] = "";
                $HTML['application_experience_button'] = "";

                $dataArray = $this->Model->getFundraisingCourseData();
                for ($i = 0; $i < count($dataArray); $i++) {
                    $uuid = uniqid(); //不讓圖片有緩存狀態的亂數
                    $course_type = $dataArray[$i]->fc_type == 0 ? '直播' : '影片';
                    if ($dataArray[$i]->fc_status == 0)
                        $fc_status = '未開始募資';
                    else if ($dataArray[$i]->fc_status == 1)
                        $fc_status = '正在募資中';
                    else if ($dataArray[$i]->fc_status == 2)
                        $fc_status = "募資成功<br>未通知";
                    else if ($dataArray[$i]->fc_status == 3)
                        $fc_status = '募資失敗';
                    else if ($dataArray[$i]->fc_status == 4)
                        $fc_status = '募資取消';
                    else if ($dataArray[$i]->fc_status == 5)
                        $fc_status = "募資成功<br>並已通知";
                    $currently = (int)$dataArray[$i]->fc_expectedNumber - (int)$dataArray[$i]->fc_remainingNumber; //目前人數
                    $HTML['content'] .= "
                        <div class=\"internal fundraising_internal row\" id=\"internal_{$i}\" onclick=\"openCollapse({$i}, 1)\" href=\"#collapseExample_{$i}\"
                             data-toggle=\"collapse\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                            <div class=\"fundraising_info col-sm-3 py-3\">
                    <div class=\"fundraising_info_left d-flex\">
                        <input type=\"checkbox\" class=\"release\" id=\"check_{$i}\" checked=\"\">
                        <span class=\"course_type\">fundraising</span>
                        <span class=\"course_id\" id=\"course_id{$i}\">{$dataArray[$i]-> fc_id}</span>
                        <img class=\"sticker col-sm-4\" src=\"../../resource/image/teacher/{$dataArray[$i]->imagePath}{$dataArray[$i]->fc_image}?value={$uuid}\">
                    </div>
                    
                    <div class=\"fundraising_info_right\">
                        <div class=\"introduction\">
                            <b>課程名稱</b> <span id=\"teach\">{$dataArray[$i]-> fc_courseName}</span> <br>
                        </div>
                    </div>
                    </div>
            
            
                            <div class=\"col-sm-12 fundraising_mobile\">
                                <div class=\"col-sm-3\">類型</div>
                                <div class=\"col-sm-4\">募資狀態</div>
                                <div class=\"col-sm-2\">募資人數</div>
                                <div class=\"col-sm-3\">功能</div>
                            </div>
            
            
            
                            <div class=\"col-sm-9 fundraising_detail\">
                                <div class=\"col-sm-3\">
                                    <h5>{$course_type}</h5>
                                </div>
                                
                                <div class=\"col-sm-3\" >
                                    <h5>{$fc_status}</h5>
                                </div>
            
                                <div class=\"col-sm-3\" >
                                    <h5>{$currently} / {$dataArray[$i]-> fc_expectedNumber}</h5>
                                </div>
            
                                <div class=\"col-sm-3 fundraising_detail_series\">
                                    <i class=\"fa fa-bell\" title=\"課程通知\" onclick=\"course_notice('F_C','{$dataArray[$i]-> fc_id}')\" ></i>
                                    <i class=\"fa fa-pencil-square\" title=\"編輯課程\" onclick=\"window.location ='../../edit_fundraisingCourse/{$dataArray[$i]-> fc_id}'\" ></i>
                                    <i class=\"fa fa-times-circle\" title=\"刪除課程\" onclick=\"delete_fundraising_course('{$dataArray[$i]-> fc_id}')\"></i>
                                    <i class=\"fa fa-plus\" title=\"展開資訊\" id=\"open_{$i}\"></i>
                                </div>
                            </div>
                        </div>
        
                        <div class=\"collapse row\" id=\"collapseExample_{$i}\">
                            <div class=\"col-sm-5 fundraising_information_detail\">
                                <p><i style=\"font-size:24px\" class=\"fa fa-diamond\"></i><b>原始價格:</b> NT$ {$dataArray[$i]-> fc_normalPrice}</p>
                                <p><i style=\"font-size:24px\" class=\"fa fa-diamond\"></i><b>募資價格:</b> NT$ {$dataArray[$i]-> fc_fundraisingPrice}</p>
                                <p><i style='font-size:26px' class='fa fa-clock-o time'></i><b>募資結束時間:</b> {$dataArray[$i]-> fc_endTime}</p>
                            </div>
                            <div class=\"col-sm-7 detail_btn_area\">
                                <button type=\"button\" class=\"btn btn-primary btn-lg\" onclick=\"fundraisingCourseToOrdinaryClass('{$dataArray[$i]-> fc_id}')\">轉換成普通課程</button>
                                <button type=\"button\" class=\"btn btn-success btn-lg\" onclick=\"fundraisingCourseToOrdinaryClassNotice('{$dataArray[$i]-> fc_id}')\">募資成功通知</button>
                                <button type=\"button\" class=\"btn btn-danger btn-lg\" onclick=\"stopFundraising('{$dataArray[$i]-> fc_id}')\">募資失敗通知</button>
                            </div>
                        </div>
                ";
                }

                $HTML['field'] .= "
                    <p class=\"col-sm-3\" id=\"fc_info\">課程資訊</p>
                    <span class=\"col-sm-9 d-flex\">
                        <p class=\"col-sm-3\">類型</p>
                        <p class=\"col-sm-3\">募資狀態</p>
                        <p class=\"col-sm-3\">募資人數</p>
                        <p class=\"col-sm-3\">功能</p>
                    </span>
                ";
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;

            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';
//            $HTML['classOption'] = $this->getClassOption("../../");
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../../', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('../../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

            $this->load->view('teacher/course_management_view', $HTML);
            $this->load->view('window/teacher/live_class_information_window');
            $this->load->view('window/teacher/experience_application_list_window');
            $this->load->view('window/teacher/course_notice_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }


    public function court_discount($id = '') //開啟堂數優惠管理頁面
    {
        if ($this->getlogin()) {
            $this->checkOneLogin();
            // 初始化變數
            $result = array();

            if (!$this->check_memberData()) {
                $result['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $result['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $result['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $result['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $result['data'] = $this->Model->getNumberLessonsPreferential($id);
            $result['classMode'] = $this->Model->getClassMode($id);
            $result['id'] = $id;
            $result['name'] = $name != '' ? $name : 'XXX';
            $result['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $result['classOption'] = $this->getClassOption("../../");
            $result['RightInformationColumn'] = $this->getRightInformationColumn('../../', $result['photo_path'], $result['name']);

            $result['headerRightBar'] = $this->getHeaderRightBar('../../', $result['photo_path'], $result['become_teacher_link'], $result['course_management_link']);
            $result['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

            $this->load->view('teacher/court_discount_view', $result);
//            $this->load->view('window/student/collection_teacher_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function setNumberLessonsPreferential()
    {
        $this->checkOneLogin();
        $data = array();
        $Data = $this->input->post();

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        $classMode = $this->Model->getClassMode($data[0]['id']);
        if ($classMode == '1') {
            $config = array(
                0 => array('key' => '', 'msg' => ''),
                1 => array('key' => 'not null', 'msg' => '請選擇貨幣'),
                3 => array('key' => '^\d+$', 'msg' => '價格只能為數字'),
            );
            $Form_normalization = $this->Form_normalization($data[0], $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            if ($this->Model->checkLiveIsNull($data[0]['id']) != 1) {
                echo json_encode(array('status' => false, 'msg' => '無此課程資料，請刷新頁面重新嘗試'));
                return;
            }
            $this->load->library('my_currency');
            if ($this->my_currency->checkCurrency($data[0]['currency'])) {
                echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
                return;
            }
            $insert = array(
                'cd_id' => uniqid(),
                'l_id' => $data[0]['id'],
                'cd_number' => -1,
                'cd_discountedPrices' => $data[0]['discountedPrices'],
                'cd_currency' => $data[0]['currency']
            );
            $this->db->trans_begin();
            $this->Model->deleteNumberLessonsPreferential($insert['l_id']);
            $this->Model->setNumberLessonsPreferentialOneStoke($insert);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '固定上課課程價格更新失敗'));
            } else {
                $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '固定上課課程價格更新成功'));
            }
            return;
        } else
            $config = array(
                0 => array('key' => '', 'msg' => ''),
                1 => array('key' => 'not null', 'msg' => '請選擇貨幣'),
                2 => array('key' => '^\d+$', 'msg' => '堂數只能為數字'),
                3 => array('key' => '^\d+$', 'msg' => '價格只能為數字'),
            );
        $insert = array();
        $this->load->library('my_currency');
        foreach ($data as $checkData) {
            $Form_normalization = $this->Form_normalization($checkData, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            if ($this->my_currency->checkCurrency($checkData['currency'])) {
                echo json_encode(array('status' => false, 'msg' => '本平台無提供此貨幣交易服務，請重新嘗試'));
                return;
            }
            $checkData = array(
                'cd_id' => uniqid(),
                'l_id' => $checkData['id'],
                'cd_number' => $checkData['number'],
                'cd_discountedPrices' => $checkData['discountedPrices'],
                'cd_currency' => $checkData['currency']
            );
            $insert[] = $checkData;
        }
        $this->db->trans_begin();
        $this->Model->deleteNumberLessonsPreferential($insert[0]['l_id']);
        $this->Model->setNumberLessonsPreferential($insert);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '堂數價格更新失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '堂數價格更新成功'));
        }
    }


    public function match_time($id = '')
    {   //開啟匹配時間頁面
        if ($this->getlogin()) {
            $this->checkOneLogin();
            if ($id == '' | $this->Model->checkMatchTime($id))
                redirect(base_url('course_management/index/type_live_course'));
            $data = $this->Model->getMatchTime($id);

            $HTML = array('Events' => array());
            foreach ($data as $index => $tempData) {
                $pepole = $tempData->maxPeople - $tempData->lastPeople;
                $HTML['Events'][$index]['id'] = $tempData->id;
                $HTML['Events'][$index]['title'] = substr($tempData->time, 11) . "\n 最多: {$tempData->maxPeople}人\n已匹配人數: {$pepole}人\n備註:{$tempData->note}";
                $HTML['Events'][$index]['start'] = substr($tempData->time, 0, 10);
                $HTML['Events'][$index]['note'] = $tempData->note;
            }

            $HTML['id'] = $id;
            $HTML['className'] = $this->Model->getClassName($id);

            if (!$this->check_memberData() | !$this->getEmailStatus()) {
                $HTML['become_teacher_link'] = '<a class="nav-link" id="test" onclick="undone_memberData()">成為老師</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
                redirect(base_url('student'));
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $HTML['course_management_link'] = '<a class="nav-link" style="float:left;" href="../../course_management/index/type_live_course">課程管理</a>';
            }

            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo . "?v=" . uniqid() : 'noPhoto.jpg';
//        $HTML['classOption'] = $this->getClassOption();
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../../', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('../../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

            $this->load->view('teacher/match_time_view', $HTML);
            $this->load->view('window/teacher/match_time_window');
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }

    public function getMatchTime($id = '')
    {
        $data = $this->Model->getMatchTime($id);
        $HTML = array('Events' => array());
        $classMode = $this->Model->getClassMode($id);
        foreach ($data as $index => $tempData) {
            $bo = true;
            if ($tempData->note == "指定學生匹配") {
                $bo = false;
                $HTML['Events'][$index]['id'] = $tempData->id;
                $HTML['Events'][$index]['title'] = substr($tempData->time, 11) . "\n 指定學生匹配";
                $HTML['Events'][$index]['start'] = substr($tempData->time, 0, 10);
            } else {
                $pepole = $tempData->maxPeople - $tempData->lastPeople;
                $HTML['Events'][$index]['id'] = $tempData->id;
                $HTML['Events'][$index]['title'] = substr($tempData->time, 11) . "\n 最多: {$tempData->maxPeople}人\n已匹配人數: {$pepole}人\n備註:{$tempData->note}";
                $HTML['Events'][$index]['start'] = substr($tempData->time, 0, 10);
            }

            if ($bo) {
                if ($classMode == '1') {
                    $HTML['Events'][$index]['id'] = $tempData->id;
                    $HTML['Events'][$index]['title'] = substr($tempData->time, 11) . "\n 固定上課時間課程";
                    $HTML['Events'][$index]['start'] = substr($tempData->time, 0, 10);
                }
            }
        }

        echo json_encode($HTML);
    }

    public function addLiveTime()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        if ($this->Model->checkAuthority() != 1) {
            echo json_encode(array('status' => false, 'msg' => '尚未發放上課專用Teams帳號，故無法新增匹配時間請耐心等待'));
            return;
        }
        if ($this->Model->checkTeahcerLiveOwnership($data['id']) != 1) {
            echo json_encode(array('status' => false, 'msg' => '您並無此課程的修改權，請勿修改程式碼'));
            return;
        }
        if ($data['Specify'] == null) {
            if ($this->Model->getClassMode($data['id']) == '1') {
                $insert = array(
                    't_id' => $_SESSION['Tid'],
                    'l_id' => $data['id'],
                    'lt_id' => uniqid(),
                    'lt_time' => $data['date'],
                    'lt_maxPeople' => 1,
                    'lt_lastPeople' => 0,
                    'lt_note' => $data['note']
                );
            } else {
                $insert = array(
                    't_id' => $_SESSION['Tid'],
                    'l_id' => $data['id'],
                    'lt_id' => uniqid(),
                    'lt_time' => $data['date'],
                    'lt_maxPeople' => $data['maxPeople'],
                    'lt_lastPeople' => $data['maxPeople'],
                    'lt_note' => $data['note']
                );
            }

            if ($this->Model->checkLiveDate($insert)) {
                if ($this->Model->addLiveTime($insert))
                    echo json_encode(array('status' => true, 'msg' => '匹配時間新增成功', 'id' => $insert['lt_id']));
                else
                    echo json_encode(array('status' => false, 'msg' => '匹配時間新增失敗，請重新嘗試'));
            } else
                echo json_encode(array('status' => false, 'msg' => '已有同一時間的匹配時間，請嘗試其他時間'));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        } else {
            if ($this->Model->checkMemberIsNull($data['Specify']) != 1) {
                echo json_encode(array('status' => false, 'msg' => '查無此學生資料'));
                return;
            }
            $insert = array(
                't_id' => $_SESSION['Tid'],
                'l_id' => $data['id'],
                'lt_id' => uniqid(),
                'lt_time' => $data['date'],
                'lt_maxPeople' => 1,
                'lt_lastPeople' => 0,
                'lt_note' => "指定學生匹配"
            );
            $insert2 = array(
                'lt_id' => $insert['lt_id'],
                'm_id' => $data['Specify']
            );
            $this->db->trans_begin();
            if ($data['designatedMod'] == 0) {

            } elseif ($data['designatedMod'] == 1) {
                $sc_id = $this->Model->getDesignated_1_shoppingData($data['Specify'], $data['id']);
                if ($sc_id == null) {
                    echo json_encode(array('status' => false, 'msg' => '此學生並無購買此課程，或者已經用完上課次數'));
                    return;
                }
                $this->Model->deductNumberOfClasses($sc_id->sc_id);
            } else {
                echo json_encode(array('status' => false, 'msg' => '請勿修改程式碼'));
                return;
            }
            $this->Model->addSpecifyMatch($insert, $insert2);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '新增指定學生匹配失敗，請重新嘗試'));
            } else {
                $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '新增指定學生匹配成功'));
            }
        }
    }

    public function editLiveTime()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $old_ltId = $data['oldid'];
        if ($this->Model->getClassMode($old_ltId) == 1) {
            $update = array(
                't_id' => $_SESSION['Tid'],
                'l_id' => $data['id'],
                'lt_time' => $data['date'],
                'lt_maxPeople' => 0,
            );
            if ($this->Model->editLiveTime($update, $old_ltId))
                echo json_encode(array('status' => true, 'msg' => '更改匹配時間成功', 'lt_id' => $update['lt_id']));
            else
                echo json_encode(array('status' => false, 'msg' => '更改匹配時間失敗，請重新嘗試'));
            return;
        } else {
            $update = array(
                't_id' => $_SESSION['Tid'],
                'l_id' => $data['id'],
                'lt_id' => uniqid(),
                'lt_time' => $data['date'],
                'lt_maxPeople' => $data['maxPeople'],
            );
        }

        if ($this->Model->editLiveTime($update, $old_ltId))
            echo json_encode(array('status' => true, 'msg' => '更改匹配時間成功', 'lt_id' => $update['lt_id']));
        else
            echo json_encode(array('status' => false, 'msg' => '更改匹配時間失敗，請重新嘗試'));
    }

    public function deleteLiveTime()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $this->db->trans_begin();
        if ($this->Model->checkMatchTimeIsMatch($data['id']) > 0) {
            echo json_encode(array('status' => false, 'msg' => '此匹配時間已經有人匹配，無法刪除'));
            return;
        }
        $this->Model->deleteLiveTime($data['id']);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '刪除匹配時間失敗'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '刪除匹配時間成功'));
        }
    }

    public
    function updateRelease($type = '')
    {
        $this->checkOneLogin();
        $Data = $this->input->post();

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        $dataArray = array();

        if ($type == 'live') {
            foreach ($data as $tempData) {
                if($tempData['type'] == 1){
                    if ($this->Model->getPriceStatus($tempData['id']) == 0) { //如果尚未設定則跳錯誤訊息
                        echo json_encode(array('status' => false, 'msg' => '有一筆勾選的課程尚未設定課程價格，請確認後再次嘗試'));
                        return;
                    }
                }

                $dataArray[] = array(
                    'l_id' => $tempData['id'],
                    'l_release' => $tempData['type']
                );
            }
        } else {
            foreach ($data as $tempData) {
                $dataArray[] = array(
                    'cf_id' => $tempData['id'],
                    'cf_release' => $tempData['type']
                );
            }
        }

        if ($this->Model->updateRelease($dataArray, $type))
            echo json_encode(array('status' => true, 'msg' => '更新發布成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '更新發布失敗'));
    }

    public
    function updateLiveURL()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '資料不完整'),
            2 => array('key' => 'not null', 'msg' => '直播網址不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        if ($this->Model->updateLiveURL($data['id1'], $data['url'], $data['id2'])) {
            $this->load->library('email');
            $this->email->set_mailtype("html");

            $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
            $emailArray = $this->Model->getStudentEmail($data['id2']);
            $emailNewArray = array();
            for ($i = 0; $i < count($emailArray); ++$i)
                array_push($emailNewArray, $emailArray[$i]['email']);
            array_push($emailNewArray, $this->Model->getTeacherEmail($_SESSION['Mid']));

            $this->email->to($emailNewArray);
            $className = $this->Model->getClassName($data['id1']);
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
                     <h1>會員您好，您購買的{$className}已經開始上課了，請點選下方按鈕前往上課。</h1>
                     <a id=\"pass\" href=\"{}\" class=\"btn btn-success text-center\">點擊前往上課</a>
                     <div>{}</div>
                </div>
            </body>
            </html>
        ";
            $this->email->subject("XXX 教學平台電子信箱驗證");
            $this->email->message($content);
            if ($this->email->send())
                echo json_encode(array('status' => true, 'msg' => '已通知上課學生，您的學生正在等您呢!'));
            else
                echo json_encode(array('status' => false, 'msg' => '通知學生上課失敗，請重新嘗試一次'));
        } else
            echo json_encode(array('status' => false, 'msg' => '更新直播網址失敗'));
    }

    public
    function cancelAttendClass()
    {
        $data = $this->Form_security_processing($this->input->post());
        if ($this->Model->cancelAttendClass($data['id']))
            echo json_encode(array('status' => true, 'msg' => '取消上課狀態成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '取消上課狀態失敗'));
    }

    public
    function getLiveURL()
    {
        $data = $this->input->post();
        echo json_encode(array('url' => $this->Model->getLiveURL($data['id'])));
    }

    public
    function deleteFundraising()
    {
        $data = $this->input->post();
        if ($this->Model->checkIsMyFundraisingCourse($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '無此募資課程的存取權'));
            return;
        }
        $fundraisingStatus = $this->Model->checkFundraisingStatus($data['id']);
        if ($fundraisingStatus == 1) {
            echo json_encode(array('status' => false, 'msg' => '此募資課程正在募資中，無法刪除'));
            return;
        } else if ($fundraisingStatus == 2) {
            echo json_encode(array('status' => false, 'msg' => '此募資課程募資成功，無法刪除'));
            return;
        }
        $imageName = $this->Model->getFundraisingCourseImage($data['id']);

        if (file_exists("resource/image/teacher/fundraisingCourse/{$imageName}")) {
            unlink("resource/image/teacher/fundraisingCourse/{$imageName}");
        } else {
            echo json_encode(array('刪除課程資料中途發生錯誤，請刷新後重新嘗試'));
            return;
        }
        if (file_exists("resource/image/teacher/fundraisingCourse/{$imageName}")) {
            echo json_encode(array('刪除課程資料中途發生錯誤，請刷新後重新嘗試'));
            return;
        }
        if ($this->Model->deleteFundraising($data['id']))
            echo json_encode(array('status' => true, 'msg' => '募資課程刪除成功'));
        else
            echo json_encode(array('stauts' => false, 'msg' => '募資課程刪除失敗'));
    }

    public
    function stopFundraising()
    {
        $data = $this->input->post();
        if ($this->Model->checkFundraisingCourse($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '無此募資課程資料，或者您無權修改此募資課程'));
            return;
        }

        if ($this->Model->checkStopFundraising($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '此募資課程目前狀態不可取消，有疑慮請聯絡客服'));
            return;
        }
        $this->db->trans_begin();
        $studentEmail = $this->Model->getFundraisingCourseStudentEmail($data['id']);
        $className = $this->Model->getFundraisingCourseName($data['id']);
        if ($studentEmail != null) {
            $this->load->library('email');
            $this->email->set_mailtype("html");
            $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
            $emailNewArray = array();
            for ($i = 0; $i < count($studentEmail); ++$i)
                array_push($emailNewArray, $studentEmail[$i]->email);
            $this->email->to($emailNewArray);

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
                     <h1>會員您好，您有興趣的{$className}募資課程募資失敗，所以通知您此課程無法順利開課，本系統將自動退還鑽石，請自行檢查是否有退還成功，如有疑慮請聯絡克服，感謝您的支持。</h1>
                     <div>{}</div>
                </div>
            </body>
            </html>
        ";
            $this->email->subject("XXX 教學平台電子信箱驗證");
            $this->email->message($content);

            $diamond = $this->Model->getDiamond($data['id']);
            $list = $this->Model->getFundraisingList($data['id']);
            if ($this->Model->stopFundraising($data['id'])) {
                if ($this->Model->returnDiamond($list, $diamond)) {
                    if ($this->email->send()) {
                        $this->db->trans_commit();
                        echo json_encode(array('status' => true, 'msg' => "{$className}取消募資成功，並寄信通知對此課程有興趣的會員"));
                    }

                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => false, 'false' => "寄信通知失敗，請重新嘗試"));
                }
            } else
                echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請重新嘗試'));
        } else {
            if ($this->Model->stopFundraising($data['id'])) {
                var_dump("aaa");
                return;
                $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => "{$className}取消募資成功，並寄信通知對此課程有興趣的會員"));
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '取消募資失敗'));
            }
        }
    }

    public
    function fundraisingCourseToOrdinaryClass()
    {
        $data = $this->input->post();
        sleep(5);

        echo json_encode(array('status' => true, 'msg' => '建智超帥'));
        return;
        if ($this->Model->checkIsMyFundraisingCourse($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '無此募資課程的存取權'));
            return;
        }
        if (!($this->Model->checkFundraisingCourseList($data['id']) > 0)) {
            echo json_encode(array('status' => false, 'msg' => '沒有對此募資課程有興趣的學生，無法轉換'));
            return;
        }

        if ($this->Model->checkFundraisingSuccess($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '此募資課程不是正在募資狀態，無法轉換成直播課程'));
            return;
        }
        if ($this->Model->checkFundraisingCourseToOrdinaryClass($data['id']) == 1) {
            echo json_encode(array('status' => false, 'msg' => '轉換失敗，此募資課程已經轉換過'));
            return;
        }

        $fundraisingCourseData = $this->Model->getFundraisingCourseDataOneRecords($data['id']);

        $image = $fundraisingCourseData->fc_image;
        if ($fundraisingCourseData->fc_type == 0) {
            $insert = array(
                't_id' => $_SESSION['Tid'],
                'l_id' => $fundraisingCourseData->fc_id,
                'l_actualMovie' => $fundraisingCourseData->fc_courseName,
                'l_experienceFilm' => $fundraisingCourseData->fc_filmUrl,
                'l_type' => $fundraisingCourseData->fc_type,
                'l_thumbnail' => $fundraisingCourseData->fc_image,
                'l_introduction' => $fundraisingCourseData->fc_courseIntroduction,
                'l_hours' => $fundraisingCourseData->fc_hours,
                'l_price' => $fundraisingCourseData->fc_normalPrice,
                'l_numberPeople' => $fundraisingCourseData->fc_expectedNumber,
                'l_status' => '0',
                'l_label' => $fundraisingCourseData->fc_label,
                //'l_currency' => $fundraisingCourseData->fc_currency,
            );
            $path = "resource/image/teacher/live/";

        } else {
            $insert = array(
                't_id' => $_SESSION['Tid'],
                'cf_id' => $fundraisingCourseData->fc_id,
                'cf_experienceFilm' => $fundraisingCourseData->fc_filmUrl,
                'cf_thumbnail' => $fundraisingCourseData->fc_image,
                'cf_name' => $fundraisingCourseData->fc_courseName,
                'cf_introduction' => $fundraisingCourseData->fc_courseIntroduction,
                'cf_hours' => $fundraisingCourseData->fc_hours,
                'cf_currency' => $fundraisingCourseData->fc_currency,
                'cf_price' => $fundraisingCourseData->fc_normalPrice,
                'cf_numberPeople' => $fundraisingCourseData->fc_expectedNumber,
                'cf_label' => $fundraisingCourseData->fc_label,
            );
            $path = "resource/image/teacher/film/";
        }
        if (!file_exists("{$path}{$image}"))
            rename("resource/image/teacher/fundraisingCourse/{$image}", "{$path}{$image}");
        if (!file_exists("{$path}{$image}")) {
            echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新後重新嘗試'));
            return;
        }
        $this->db->trans_begin();
        $this->Model->fundraisingCourseToOrdinaryClass($insert, $fundraisingCourseData->fc_type);
        $this->Model->fundraisingCourseStatus($data['id']);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '轉換課程失敗，請重新嘗試'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '轉換課程成功，請去相對應的課程管理頁面完成更詳細的設定'));
        }


    }

    public
    function fundraisingCourseToOrdinaryClassNotice()
    {
        $data = $this->input->post();
        sleep(5);

        echo json_encode(array('status' => true, 'msg' => '建智超帥'));
        return;
        if ($this->Model->checkFundraisingCourseToOrdinaryClassNotice($data['id']) != 1) {
            echo json_encode(array('status' => false, 'msg' => '請先將募資課程轉換成直播或影片課程，再嘗試通知學生'));
            return;
        }
        $list = $this->Model->getFundraisingCourseList($data['id']);
        if ($list == null) {
            echo json_encode(array('status' => false, 'msg' => '沒有對此募資課程有興趣的學生，無法通知'));
            return;
        }

        if ($this->Model->checkFundraisingCourseToOrdinaryClassNoticeStatus($data['id']) == 1) {
            echo json_encode(array('status' => false, 'msg' => '此募資課程已經通知有興趣的學生了，請勿重複操作'));
            return;
        }

        $studentEmail = $this->Model->getFundraisingCourseStudentEmail($data['id']);
        if ($studentEmail != null) {
            $this->load->library('email');
            $this->email->set_mailtype("html");
            $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
            $emailNewArray = array();
            for ($i = 0; $i < count($studentEmail); ++$i)
                array_push($emailNewArray, $studentEmail[$i]->email);

            $this->email->to($emailNewArray);
            $className = $this->Model->getFundraisingCourseName($data['id']);

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
                     <h1>會員您好，您有興趣的{$className}募資課程募資成功，系統已將課程自動加入您的購物車中，您可以用募資價格來購買此課程。</h1>
                     <div>{}</div>
                </div>
            </body>
            </html>
        ";
            $this->email->subject("XXX 教學平台電子信箱驗證");
            $this->email->message($content);
            $this->db->trans_begin();
            if ($this->Model->successFundraisingList($data['id'], $list))
                if ($this->email->send()) {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => true, 'msg' => "{$className}募資成功，並寄信通知對此課程有興趣的會員"));
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => false, 'false' => "寄信通知失敗，請重新嘗試"));
                }
            else
                echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請重新嘗試'));
        }
    }

    public
    function addCourseNotice()
    {
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '通知對象不可為空'),
            1 => array('key' => '', 'msg' => ''),
            2 => array('key' => 'not null', 'msg' => '寄信或通知選項不可為空'),
            3 => array('key' => 'not null', 'msg' => '通知訊息標題不可為空'),
            4 => array('key' => 'not null', 'msg' => '通知訊息不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($data['notice_object'] == 0 | $data['notice_object'] == 1 | $data['notice_object'] == 2) {
            $insert = array(
                'nr_sendIdentity' => 'A',
                'nr_noticeObject' => $_SESSION['Tid'],
                'nr_messageTitle' => $data['message_title'],
                'nr_sendMessage' => $data['send_message'],
                'nr_emailOrNotice' => $data['email_or_notice'],
                'nr_date' => date('Y/m/d H:i:s'),
            );
        } else {
            if ($data['notice_object'] == 3)
                if ($this->Model->checkMemberIsNull($data['specificObject']) != 1) {
                    echo json_encode(array('status' => false, 'msg' => '查無此會員資料，請確認後再操作'));
                    return;
                } elseif ($data['notice_object'] == 4)
                    if ($this->Model->checkTeacherIsNull($data['specificObject']) != 1) {
                        echo json_encode(array('status' => false, 'msg' => '查無此老師資料，請確認後再操作'));
                        return;
                    } elseif ($data['notice_object'] == 5)
                        if ($this->Model->checkLiveIsNull($data['specificObject']) != 1) {
                            echo json_encode(array('status' => false, 'msg' => '查無此直播課程，請確認後再操作'));
                            return;
                        } elseif ($data['notice_object'] == 6)
                            if ($this->Model->checkFilmIsNull($data['specificObject']) != 1) {
                                echo json_encode(array('status' => false, 'msg' => '查無此影片課程，請確認後再操作'));
                                return;
                            } elseif ($data['notice_object'] == 7)
                                if ($this->Model->checkFundraisingIsNull($data['specificObject']) != 1) {
                                    echo json_encode(array('status' => false, 'msg' => '查無此募資課程，請確認後再操作'));
                                    return;
                                } else {
                                    echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面後重新嘗試'));
                                    return;
                                }
            $insert = array(
                'nr_sendIdentity' => 'T',
                'nr_noticeObject' => $data['notice_object'],
                'nr_specificObject' => $data['specificObject'],
                'nr_messageTitle' => $data['message_title'],
                'nr_sendMessage' => $data['send_message'],
                'nr_emailOrNotice' => $data['email_or_notice'],
                'nr_date' => date('Y/m/d H:i:s'),
            );
        }

        if ($this->Model->checkRepeatNotice($insert) > 10) {
            echo json_encode(array('status' => false, 'msg' => '請勿重複新曾相同內容的通知，或放慢新增通知的速度'));
            return;
        }

        if ($data['email_or_notice'] == 2) {
            if ($this->Model->addNoticeRecord($insert))
                echo json_encode(array('status' => true, 'msg' => '通知新增成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '通知新增失敗'));
        } else {
            $this->db->trans_begin();
            if ($this->Model->addNoticeRecord($insert)) {
                $this->load->library('email');
                $this->email->set_mailtype("html");

                $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
                $emailArray = $this->Model->getNoticeEmail($insert['nr_noticeObject'], $data['specificObject']);
                if (count($emailArray) == 0) {
                    echo json_encode(array('status' => false, 'msg' => '此通知對象找不到對應的信箱，請改用通知方式'));
                    return;
                }
                $emailNewArray = array();
                for ($i = 0; $i < count($emailArray); ++$i)
                    array_push($emailNewArray, $emailArray[$i]->m_email);

                $this->email->to($emailNewArray);
                $this->email->subject($insert['nr_messageTitle']);
                $this->email->message($insert['nr_sendMessage']);


                if ($this->db->trans_status() === TRUE & $this->email->send())
                    $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '通知新增成功'));
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '通知新增失敗'));
            }
        }


    }
}
