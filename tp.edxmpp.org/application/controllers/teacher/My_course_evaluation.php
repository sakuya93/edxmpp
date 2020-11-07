<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_course_evaluation extends Infrastructure
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("my_course_evaluation_model", "Model", TRUE);
    }

    public function index($sel_type = '', $sel_index = '')
    {
        $data = new stdClass();
        if ($this->getlogin()) {
            $this->checkOneLogin();

            //初始化變數
            $HTML = array(
                'content' => '',
                'name' => '',
                'photo_path',
                'course_option' => '',
                'header_title' => '',
            );
            //初始化變數結束
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
            $nav = $this->get_nav();
            $name = $nav->name;
            $photo = $nav->photo;
            $HTML['name'] = $name != '' ? $name : 'XXX';
            $HTML['photo_path'] = $photo != '' ? $photo : 'noPhoto.jpg';
//            $HTML['classOption'] = $this->getClassOption('../../');
            $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../../', $HTML['photo_path'], $HTML['name']);

            $HTML['headerRightBar'] = $this->getHeaderRightBar('../../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
            $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

            /*取得課程選項 start*/
            $Course_Option = $this->Model->getAllCourseOption($_SESSION['Tid']);

            foreach ($Course_Option as $key => $value) {
                $index = 0;
                if ($key == 'live') {
                    foreach ($value as $key1 => $live_Course_value) {
                        if ($key1 == 0) {
                            $HTML['course_option'] .= "<h5 class=\"dropdown-header option_header\">直播課程</h5>";
                        }
                        $HTML['course_option'] .= "<a class=\"dropdown-item\" href=\"../../my_course_evaluation/live/$index\">{$live_Course_value['l_actualMovie']}</a>";
                        ++$index;
                    }
                } else {
                    foreach ($value as $key1 => $film_Course_value) {
                        if ($key1 == 0) {
                            $HTML['course_option'] .= "<h5 class=\"dropdown-header option_header\">影片課程</h5>";
                        }
                        $HTML['course_option'] .= "<a class=\"dropdown-item\" href=\"../../my_course_evaluation/courseFilm/$index\">{$film_Course_value['cf_name']}</a>";
                        ++$index;
                    }
                }

            }
            /*取得課程選項 end*/

            if ($sel_type == 'live') {
                $data = $this->Model->getCourse_st_data($Course_Option[$sel_type][$sel_index]["l_actualMovie"]);
                $HTML['header_title'] = "我的課程評價 - {$Course_Option[$sel_type][$sel_index]["l_actualMovie"]} (直播課程)";
            } else {
                $data = $this->Model->getCourse_st_data($Course_Option[$sel_type][$sel_index]["cf_name"]);
                $HTML['header_title'] = "我的課程評價 - {$Course_Option[$sel_type][$sel_index]["cf_name"]} (影片課程)";
            }

            if ($data == null) {
                //形成ERROR HTML
                $HTML['content'] .= "<h5 style='padding: 10px;margin-top: 10px;color: grey;font-size: 24px'>目前並無任何學生購買您的課程</h5>";
            } else {
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i]->l_id == null) {
                        $type = '影片';
                        $filmData = $this->Model->getFilmData($data[$i]->cf_id);
                        if (isset($filmData[$i]->cf_type))
                            $data[$i]->type = $filmData[$i]->cf_type;
                    } else {
                        $type = '直播';
                    }
                    $data[$i]->m_photo .= "?value=" . uniqid();

                    /*星數形成 start*/
                    $star_num = $data[$i]->score; //預設5顆星 ; 最少0 最多5
                    $star_content = "";
                    $star_count = 0; //用來計算目前幾顆星
                    for ($j = 0; $j < $star_num; $j++) { //填滿的星數形成
                        $star_content .= "<i class=\"fa\">&#xf005;</i>";
                        $star_count++;
                    }

                    for ($j = $star_count; $j < 5; $j++) { //沒填滿的星數形成(補齊五顆星)
                        $star_content .= "<i class=\"fa\">&#xf006;</i>";
                    }
                    /*星數形成 end*/

                    /*將null資料轉成'未設定'字串 start*/
//                    $data[$i]->m_phoneNumber = $data[$i]->m_phoneNumber == null ? "未設定" : $data[$i]->m_phoneNumber;
                    $data[$i]->m_line = $data[$i]->m_line == null ? "未設定" : $data[$i]->m_line;
                    $data[$i]->m_email = $data[$i]->m_email == null ? "未設定" : $data[$i]->m_email;
                    /*將null資料轉成'未設定'字串 end*/

                    $HTML['content'] .= "
                    <div class=\"row course_type\">
                        <div class=\"row col-sm-12\"> <!-- 商品 -->
                            <div class=\"course_info_block row col-sm-6\"> <!-- 課程資訊 -->
                                <div class=\"course_info_img col-sm-5\">
                                    <img class=\"rounded-circle\"
                                         src=\"../../resource/image/student/photo/{$data[$i]->m_photo}\"
                                         height=\"168\"
                                         width=\"168\">
                                </div>
                                <div class=\"course_info_detail row col-sm-7\">
                                    <div class=\"col-sm-12\">評價者: {$data[$i]->m_name}</div>
                                    <div class=\"col-sm-12\" style=\"color:#de605a;font-weight:bold\">課程: {$data[$i]->sc_className}</div>
                                    <div class=\"col-sm-12\">來自: {$data[$i]->m_country}</div>
                                    <div class=\"col-sm-12\">會說: {$data[$i]->m_speakLanguage}</div>
                                </div>
                            </div>
                            <div class=\"course_detail_block row col-sm-6\"> <!-- 詳細 -->
                                <div class=\"course_detail_field row col-sm-4\"> <!-- 詳細欄位 -->
                                    <div class=\"col-sm-12\">形式:</div>
                                    <div class=\"col-sm-12\">類別:</div>
                                    <div class=\"col-sm-12\">居住地:</div>
                                    <div class=\"col-sm-12\">LINE:</div>
                                    <div class=\"col-sm-12\">信箱:</div>
                                </div>
                                <div class=\"course_detail_info row col\"> <!-- 詳細資訊 -->
                                    <div class=\"col-sm-12\">$type</div>
                                    <div class=\"col-sm-12\">{$data[$i]->type}</div>
                                    <div class=\"col-sm-12\">{$data[$i]->m_city}</div>
                                    <div class=\"col-sm-12\">{$data[$i]->m_line}</div>
                                    <div class=\"col-sm-12\">{$data[$i]->m_email}</div>
                                </div>
                                <div class=\"col-sm-2 tools\">
                                    <i class=\"fa fa-plus\" onclick=\"openCollapse({$i}, 1)\" id=\"open_{$i}\" data-toggle=\"collapse\" href=\"#collapseExample_{$i}\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\"></i>
                                </div>
                            </div>
                            
                            <div class=\"collapse evaluation_collapse row\" id=\"collapseExample_{$i}\">
                                <div class=\"col-sm-12 row\">
                                     <div class='score-body col-sm-6'>
                                           <p>評分:</p>
                                           {$star_content}
                                     </div>
                                     <div class='comment-body col-sm-6'>
                                           <p>評語:</p>
                                           <textarea rows='8' cols='80' class='comment_area' readonly>{$data[$i]->comment}</textarea>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
            }

            $this->load->view('teacher/my_course_evaluation_view', $HTML);
            $this->load->view('window/share/notice_window');
            $this->load->view('window/hint_window');
        } else
            redirect(base_url('home'));
    }
}
