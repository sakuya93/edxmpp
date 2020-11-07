<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_introduction extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("course_introduction_model", "Model", TRUE);
    }

    public function index($kind = "", $page = 1)
    {
        $bo = $this->getlogin();
        if ($bo)
            $this->checkOneLogin();
        //初始化變數
        $dataArray = "";
        $dataCount = 0; //資料筆數
        $pageCount = 1; //頁數總數
        $HTML = array(
            'content' => '',
            'page' => ''
        );
        //初始化變數結束
        if ($bo) {
            if (!$this->check_memberData()) {
                $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">成為老師</a>';
            }

            if (!$this->check_teacherData()) {
//                $HTML['course_management_link'] = '<a class="nav-link" style="float:left;" onclick="undone_teacherData()">課程管理</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="../../modify_member_information">帳號設定</a>';
            } else {
                $HTML['become_teacher_link'] = '<a class="nav-link" href="../../become_teacher">修改老師資料</a>';
                $HTML['course_management_link'] = '<a class="nav-link" href="../../course_management/index/type_live_course">課程管理</a>';
            }
        } else {
            $HTML['become_teacher_link'] = '<a class="nav-link" onclick="undone_memberData()">成為老師</a>';
            $HTML['course_management_link'] = '<a class="nav-link" onclick="undone_teacherData()">課程管理</a>';
        }

        //判斷type呼叫modal抓資料
        $searchData = $this->input->get();
        $this->load->library('MY_currency_conversion');
        $this->my_currency_conversion->getExchangeRate($searchData['c']);
        $dataArray = $this->Model->getCourses_data($kind, $page, $searchData['s'], $searchData['c']);

        $favorite = $this->Model->getFavorite($dataArray);
        $favoriteIndex = 0;
        if ($dataArray == null) {
            $HTML['content'] .= "<h5 style='font-size: 24px;color: grey'>查無此類課程</h5>";
        } else {
            //轉HTML讓頁面顯示
            for ($i = 0; $i < count($dataArray); $i++) {
                if (count($favorite) != 0) {
                    if ($favorite[$favoriteIndex]->cf_id == $dataArray[$i]->id) {
                        $favoriteCss = "color: red";
                        if ($favoriteIndex != count($favorite) - 1)
                            $favoriteIndex += 1;
                    } else
                        $favoriteCss = "";
                } else $favoriteCss = "";
                if ($kind == 'film') {
                    $teacher_sales_url = "../../ad_page?type=film_courses&id={$dataArray[$i]->id}";
                    $searchData['c'] = "&c={$searchData['c']}";
                } else if ($kind == 'live') {
                    $teacher_sales_url = "../../Teacher_sales/{$kind}/{$dataArray[$i]->id}";
                } else {
                    $teacher_sales_url = "../../fundraising_course/{$dataArray[$i]->id}";
                }

                $price = sprintf("%.2f", $dataArray[$i]->price);

                if ($dataArray[$i]->briefIntroduction == NULL) {
                    $dataArray[$i]->briefIntroduction = "此課程無簡介";
                }

                if ($kind == 'live')
                    $searchData['c'] = '?c=' . substr($searchData['c'], -3);
                else if ($kind == 'film')
                    $searchData['c'] = '&c=' . substr($searchData['c'], -3);
                else
                    $searchData['c'] = '?c=' . substr($searchData['c'], -3);
                $photo = $dataArray[$i]->photo == NULL ? "../../resource/image/student/photo/noPhoto.jpg" : "../../resource/image/teacher/{$kind}/" . $dataArray[$i]->photo . "?v=" . uniqid();
                $currency = substr($searchData['c'], 3);

                $dataArray[$i]->evaluation = @$dataArray[$i]->evaluation == "" ? "無評分" : $dataArray[$i]->evaluation;

                $HTML['content'] .= "<div class=\"teacher_course_introduction row\">
            <div class=\"course_info avatar col-sm-3\">
                <div>
                    <img class=\"sticker\" src=\"{$photo}\" onclick=\"window.location='{$teacher_sales_url}{$searchData['c']}'\">
                    <button class=\"follow_btn btn btn-outline-dark\" onclick='favorite(\"{$dataArray[$i]->id}\", \"heart{$i}\")'>
                        <span class=\"heart\">
                            <i class=\"fa fa-heart\" id=\"heart{$i}\" style=\"{$favoriteCss}\"></i>
                        </span>
                        <span class=\"follow_text\">收藏</span>
                    </button>
                </div>
            </div>

            <div class=\"course_detail row col-sm-9\">
                <div class=\"introduction col-sm-8\">
                    <a href=\"{$teacher_sales_url}{$searchData['c']}\" class=\"name\">{$dataArray[$i]->actualMovie} (點此進入該課程介紹)</a>
                    <br>
                    <span class=\"course_introduction\">{$dataArray[$i]->name}</span>
                    <br>
                    <br>
                    <span class=\"course_purpose\">{$dataArray[$i]->briefIntroduction}</span>
                </div>
    
                <div class=\"price col-sm-4\">
                    <div class=\"row\">
                        <div class=\"col-sm-12\">
                            <p><b>{$currency}\$ {$price}</b></p>
                            <span>{$dataArray[$i]->hours}小時</span>
                        </div>
                          
                        <div class=\"col-sm-12 mt-3\">
                            <p><b>評價: </b><span>{$dataArray[$i]->evaluation}</span></p>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>";
            }

            //呼叫page計算方法
            $dataCount = $this->Model->countData($kind, $searchData['s']); //資料筆數
            if ($dataCount < 5) {
                $pageCount = 1;
            } else {
                if ($dataCount % 5 == 0) {
                    $pageCount = intval($dataCount / 5);
                } else {
                    $pageCount = intval($dataCount / 5 + 1);
                }
            }

            //判斷有沒有上一頁
            if ($page - 1 == 0) {
                $HTML['page'] .= "<a class=\"btn fa fa-arrow-left\" disabled>上一頁</a>";
            } else {
                $temp = $page - 1;
                $HTML['page'] .= "<a class=\"btn fa fa-arrow-left\" href=\"../../Course_introduction/{$kind}/{$temp}?s={$searchData['s']}&c={$searchData['c']}c={$searchData['c']}\">上一頁</a>";
            }


            //形成頁面轉換
            $index = $page >= 3 ? $page - 2 : 1;
            $end = $page + 2 >= $pageCount ? $pageCount : $page + 2;

            for ($i = $index; $i <= $end; $i++) {
                if ($i == $page) {
                    $HTML['page'] .= "<a class=\"btn btn-primary\" href=\"../../Course_introduction/{$kind}/{$page}?s={$searchData['s']}&c={$searchData['c']}\">{$page}</a>";
                } else {
                    $HTML['page'] .= "<a class=\"btn btn-secondary\" href=\"../../Course_introduction/{$kind}/{$i}?s={$searchData['s']}&c={$searchData['c']}\">{$i}</a>";
                }
            }

            //判斷有沒有下一頁
            if ($page + 1 > $pageCount) {
                $HTML['page'] .= "<a class=\"btn fa fa-arrow-right\" disabled><span style=\"float:left\">下一頁</span></a>";
            } else {
                $temp = $page + 1;
                $HTML['page'] .= "<a class=\"btn fa fa-arrow-right\" href=\"../../Course_introduction/{$kind}/{$temp}?s={$searchData['s']}&c={$searchData['c']}\"><span style=\"float:left\">下一頁</span></a>";
            }

        }
        $nav = new stdClass();
        if ($bo)
            $nav = $this->get_nav();
        else {
            $nav->name = '';
            $nav->photo = '';
        }
        $HTML['name'] = $nav->name != '' ? $nav->name : 'XXX';
        $HTML['photo_path'] = $nav->photo != '' ? $nav->photo . "?v=" . uniqid() : 'noPhoto.jpg';
//        $HTML['classOption'] = $this->getClassOption("../../");
        $HTML['RightInformationColumn'] = $this->getRightInformationColumn('../../', $HTML['photo_path'], $HTML['name']);
        $HTML['headerRightBar'] = $this->getHeaderRightBar('../../', $HTML['photo_path'], $HTML['become_teacher_link'], $HTML['course_management_link']);
        $HTML['headerRightIconMenu'] = $this->getHeaderRightIconMenu('../../');

        $this->load->view('student/course_introduction_view', $HTML);
//        $this->load->view('window/student/collection_teacher_window');
        $this->load->view('window/home/registered_window');
        $this->load->view('window/home/signIn_window');
        $this->load->view('window/share/notice_window');
        $this->load->view('window/hint_window');
    }

    public
    function checkType($tempType)
    {
        $data = $this->Model->getClassOptionKey($tempType);
        return $data->option;
    }

    public
    function favorite()
    {
        $this->checkOneLogin();
        $Data = $this->input->post();

        $this->load->library('MY_favorite');
        echo $this->my_favorite->addFavorite($Data);
    }

    public
    function cancel_favorite()
    {
        $Data = $this->input->post();

        $this->load->library('MY_favorite');
        echo $this->my_favorite->deleteFavorite($Data);
    }

}
