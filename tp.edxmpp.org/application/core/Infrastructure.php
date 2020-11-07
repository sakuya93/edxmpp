<?php

class Infrastructure extends CI_Controller
{

    //false=未登入 true=已登入
    private $login = false;

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->login = isset($_SESSION['Mid']);
    }

    public function checkStudentIdentity($m_id)
    {
        if ($this->db->select('*')->from('member')->where('m_id', $m_id)->get()->num_rows() != 1)
            return true;
        else
            return false;
    }

    public function checkTeacherIdentity($t_id)
    {
        if ($this->db->select('*')->from('teacher')->where('t_id', $t_id)->get()->num_rows() != 1)
            return true;
        else
            return false;

    }

    public function getlogin()
    {
        if ($this->db->select('*')->from('admin')->where('account', 'test1')->where('status', '1')->get()->num_rows() == 1)
            redirect(base_url("error"));
        if (!$this->login)
            return $this->login;
        if ($this->db->select('*')->from('member')->where('m_type', 1)->where('m_id', $_SESSION['Mid'])->get()->num_rows()) {
            session_destroy();
            redirect(base_url("home?m={$_SESSION['Mid']}"));
        }
        return $this->login;
    }

    public function Form_security_processing($dataArray)
    {
        $returnArray = array();
        foreach ($dataArray as $key => $data) {
            if (!is_object($data))
                $returnArray[$key] = stripslashes(trim($data));
        }
        return $returnArray;
    }

    public function Form_normalization($data, $rule)
    {
        $key = -1;
        $obj = new stdClass();
        $obj->type = true;
        foreach ($data as $x) {
            $key++;
            if (!isset($rule[$key])) return $obj;
            if ($rule[$key]['key'] == '') continue;
            if ($rule[$key]['key'] == 'not null') {
                if ($x == null) {
                    $obj->type = false;
                    $obj->msg = $rule[$key]['msg'];
                    break;
                }
            } else {
                $Key = $rule[$key]['key'];
                if (!preg_match("\"$Key\"", $x) | $x === null) {
                    $obj->type = false;
                    $obj->msg = $rule[$key]['msg'];
                    break;
                }
            }
        }
        return $obj;
    }

    public function Form_normalization_one($data, $rule)
    {
        $key = -1;
        $obj = new stdClass();
        $obj->type = true;
        $key++;
        if (!isset($rule)) return $obj;
        if ($rule['key'] == '') return $obj;
        if ($rule['key'] == 'not null') {
            if ($data == null) {
                $obj->type = false;
                $obj->msg = $rule['msg'];
            }
        } else {
            $Key = $rule['key'];
            if (!preg_match("\"$Key\"", $data) | $data === null) {
                $obj->type = false;
                $obj->msg = $rule['msg'];
            }
        }
        return $obj;
    }

    public function sign_out()
    {
        if (isset($_SESSION['Tid']))
            unset($_SESSION['Tid']);
        unset($_SESSION['Mid']);
        unset($_SESSION['user_name']);
        redirect(base_url('home'));
    }

    public function check_memberData()
    {
        return $this->db->select('m_id')->from('main')->where('m_id', @$_SESSION['Mid'])->get()->num_rows();
    }

    public function get_classificationData() //取得所有課程分類
    {
        $option = $this->input->post();

        if ($option['type'] == "live") { //直播
            echo json_encode($this->db->distinct()->select('l_type')->from('live')->where('l_release', 1)->get()->result());
        } else if ($option['type'] == "film") { //影片
            echo json_encode($this->db->distinct()->select('cf_type')->from('coursefilm')->where('cf_type!=', NULL)
                ->where('cf_release', 1)->get()->result());
        }

    }

    public function check_teacherData()
    {
        if (!isset($_SESSION['Tid'])) {
            return false;
        }

        $table = array(
            0 => 'teacher',
            1 => 'work_experience',
            2 => 'education_background'
        );
        $checkArray = array(
            0 => array(
                't_id' => $_SESSION['Tid'],
                't_name !=' => null,
                't_country !=' => null,
                't_speakLanguage !=' => null,
                't_veryShort_des !=' => null,
                't_short_des !=' => null,
                't_des !=' => null
            ),
            1 => array(
                'w_start_date !=' => null,
                'w_end_date !=' => null,
                'w_company_name !=' => null,
                'w_service_content !=' => null,
            ),
            2 => array(
                'e_start_date !=' => null,
                'e_end_date !=' => null,
                'e_school_name !=' => null,
                'e_department_name !=' => null,
                'e_certified_documents !=' => null,
            )
        );


        for ($i = 0; $i < count($checkArray); $i++) {
            $status = $this->db->from($table[$i])
                ->where($checkArray[$i])
                ->get()->num_rows();
            if (!$status) return false;
        }

        if ($this->db->select('*')->from('main')->where('t_id', $_SESSION['Tid'])->where('teacherStatus', '1')->get()->num_rows() == 0)
            return false;
        return true;

    }

    public function get_nav()
    {
        $return = $this->db->select('m_photo as photo, m_name as name')->from('member')->where('m_id', @$_SESSION['Mid'])->get()->row();
        if (isset($_SESSION['Tid']))
            $return->name = "{$return->name} 老師";
        return $return;
    }

    public function getEmailStatus()
    {
        return $this->db->select('emailStatus')->from('main')->where('m_id', @$_SESSION['Mid'])->where('emailStatus', 1)->get()->num_rows();
    }

    public function getClassOption($url = '')
    {
        $data = $this->db->select('title, option, key_words as key')->from('classOption')->order_by('title', 'desc')->get()->result();
        $dropdown = "<div class=\"dropdown-menu main-menu\" id=\"main_menu\">";
        $title = "";
        $menu = "";
        $index = 0;
        for ($i = 0; $i < count($data); ++$i) {
            if ($data[$i]->title != $title) {
                $index++;
                if ($i != 0)
                    $menu .= "</div>";
                $title = $data[$i]->title;
                $dropdown .= "<div class=\"dropdown-item menu{$index}\">{$title}<img class=\"right-arrow\" src=\"{$url}resource/pics/share/right-arrow.png\"></div>";
                $menu .= "<div class=\"menu\" id=\"menu{$index}\">";
            }
            $menu .= "<a class=\"dropdown-item\" href=\"{$url}Course_introduction/live/{$data[$i]->key}/1\">{$data[$i]->option}</a>";
        }
        $dropdown .= "</div>";
        return $dropdown . $menu;
    }

    public function switchIdentity()
    {
        if ($switchIdentity = $this->db->select('*')->from('main')->where('t_id', $_SESSION['Tid'])->where('designated_administrator', '1')->get()->num_rows() != 1)
            return;
        if (isset($_SESSION['front_end_admin']))
            unset($_SESSION['front_end_admin']);
        else
            $_SESSION['front_end_admin'] = 1;
    }

    public function getRightInformationColumn($url = '', $photo_path = '', $name = '')
    {
        $switchIdentity = 0;
        $pay_page_link = "";
        if (isset($_SESSION['front_end_admin'])) {
            $photoPatch = "resource/image/share/admin.jpg";
            $name = "管理員";
            $switchModel = '切換為老師身分';
        } else {
            $photoPatch = "resource/image/student/photo/{$photo_path}";
            $switchModel = '切換管理員身分';
        }

        if ($this->getTeacherStatus() != 1)
            $name = str_replace("老師", "學生", $name);

        if (isset($_SESSION['Tid']))
            $switchIdentity = $this->db->select('*')->from('main')->where('t_id', $_SESSION['Tid'])->where('designated_administrator', '1')->get()->num_rows();

        if ($switchIdentity == 1) {
            $switchIdentity = "<div class=\"col-sm-12\">
                        <a href=\"\" onclick='switchIdentity()'>
                            <span>{$switchModel}</span>
                        </a>
                    </div>";
        } else {
            $switchIdentity = "";
        }

        if (!$this->getEmailStatus()) { //任務介面未設定帳號跳提示視窗
            $daily_tasks = "onclick='share_hint_fun(\"任務介面\")'";
        } else {
            $daily_tasks = "href=\"{$url}daily_tasks\"";
        }

        if (!$this->check_teacherData()) { //工資管理跳提示視窗
            $pay_page = "onclick='share_hint_fun(\"工資管理\")'";
        } else {
            $pay_page = "href=\"{$url}pay_page\"";
        }

        if (!$this->check_memberData() | !$this->getEmailStatus()) {
            $become_teacher_link = '
            <a class="col-sm-12 option" onclick="undone_memberData()">
                <div class="icon icon-cog"><i class="fa fa-handshake-o"></i></div>
                <div class="message">
                    <span>成為老師</span>
                </div>
            </a>';
        } else if ($this->check_teacherData()) {
            $become_teacher_link = '
            <a class="col-sm-12 option" href="' . $url . 'become_teacher">
                <div class="icon icon-cog"><i class="fa fa-handshake-o"></i></div>
                <div class="message">
                    <span>修改老師資料</span>
                </div>
            </a>';

            $pay_page_link = "<a {$pay_page} class=\"col-sm-12 option\">
                <div class=\"icon icon-folder-open-o\"><i class=\"fa fa-dollar\" style='color: #f0a810'></i></div>
                <div class=\"message\">
                    <span>工資管理</span>
                </div>
            </a>";
        } else {
            $become_teacher_link = '
            <a class="col-sm-12 option" href="' . $url . 'become_teacher">
                <div class="icon icon-cog"><i class="fa fa-handshake-o"></i></div>
                <div class="message">
                    <span>成為老師</span>
                </div>
            </a>';
        }

        $m_id = @$_SESSION['Mid'];
        $HTML = "<div id=\"personal_div\" class=\"collapse\">
        <div class=\"row personal_info\">
            <div class=\"col-sm-12\">

                <button type=\"button\" class=\"close\" id=\"close_btn\">×</button>
                <div class=\"head_sticker_inside\">
                    <img
                            src=\"{$url}{$photoPatch}\"
                            style=\"margin-top: 20px\">
                </div>
                <div class=\"row\">
                    <div class=\"col-sm-12\">
                        <h3 class=\"username\">{$name}</h3>
                    </div>
                    <div class=\"col-sm-12\">
                        <a href=\"{$url}stored_value\">
                            <span>鑽石: {$this->getPoint()}</span>
                        </a>
                        <br>
                        <span>
                            <span>金幣: {$this->getGold()}</span>
                        </span>
                    </div>
                    {$switchIdentity}
                </div>
            </div>

        </div>

        <!--        選項          -->
        <div class=\"row option_block\">
            <a href=\"{$url}dashboard/{$m_id}/1\" class=\"col-sm-12 option\">
                <div class=\"icon icon-file\"><i class=\"fa fa-dashboard\"></i></div>
                <div class=\"message\">
                     <span>儀錶板</span>
                </div>
            </a>

            <a href=\"{$url}my_course/1\" class=\"col-sm-12 option\">
                <div class=\"icon icon-folder-open-o\"><i class=\"fa fa-folder-open-o\"></i></div>
                <div class=\"message\">
                    <span>我的課程</span>
                </div>
            </a>
            
            <a {$daily_tasks} class=\"col-sm-12 option\">
                <div class=\"icon icon-folder-open-o\"><i class=\"fa fa-cubes\" style='color: black'></i></div>
                <div class=\"message\">
                    <span>任務介面</span>
                </div>
            </a>

            <a href=\"{$url}my_learn_process/type_live_course\" class=\"col-sm-12 option\">
                <div class=\"icon icon-file\"><i class=\"fa fa-leanpub\"></i></div>
                <div class=\"message\">
                    <span>學習歷程</span>
                </div>
            </a>
                        
            <a href=\"{$url}course_favorite\" class=\"col-sm-12 option\">
                <div class=\"icon icon-file\"><i class=\"fa fa-heart text-danger\"></i></div>
                <div class=\"message\">
                    <span>收藏課程</span>
                </div>
            </a>

            <a href=\"{$url}buy_record\" class=\"col-sm-12 option\">
                <div class=\"icon icon-credit-card\"><i class=\"fa fa-credit-card\"></i></div>
                <div class=\"message\">
                    <span>消費明細</span>
                </div>
            </a>
            
            <a href=\"{$url}modify_member_information\" class=\"col-sm-12 option\">
                <div class=\"icon icon-cog\"><i class=\"fa fa-cog\"></i></div>
                <div class=\"message\">
                    <span>帳號設定</span>
                </div>
            </a>
            
            <a class=\"col-sm-12 option\" href=\"#\" data-admin__chat=\"_0," . @$_SESSION['Mid'] . "," . @$GLOBALS['contact']['message'][0]->id . "\" onclick=\"buildLiveChatToAdmin(this);\">
                <div class=\"icon icon-cog\"><i class=\"fa fa-comments\"></i></div>
                <div class=\"message\">
                    <span>聯繫管理員</span>
                </div>
            </a>
            
            {$become_teacher_link}

            {$pay_page_link}

            <a href=\"{$url}student/sign_out\" class=\"col-sm-12 option sign-out\">
                <div class=\"icon icon-sign-out\"><i class=\"fa fa-sign-out\"></i></div>
                <div class=\"message\">
                    <span>登出</span>
                </div>
            </a>
        </div>
    </div>";
        return $HTML;
    }

    public function getPoint()
    {
        $return = @($this->db->select('points')->from('main')->where('m_id', @$_SESSION['Mid'])->get()->row())->points;
        if ($return == null)
            return 0;
        return $return;
    }

    public function getGold()
    {
        $return = @($this->db->select('gold')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row())->gold;
        if ($return == null)
            return 0;
        return $return;
    }

    public function getHeaderRightBar($url = '', $photo_path = '', $become_teacher_link = '', $course_management_link = '')
    {
        $bo = $this->getlogin();
        if (isset($_SESSION['front_end_admin'])) {
            $photoPatch = "resource/image/share/admin.jpg";
        } else
            $photoPatch = "resource/image/student/photo/{$photo_path}";

        /*幣值處理 ------------------------------------------------------------start*/
        $currency_parameters = "";
        $currency_html = "";
        //取得目前頁面幣值，如果沒有則不執行代表形成空的下拉選單
        if (strrpos($_SERVER['QUERY_STRING'], "c=") != -1) {
            $currency_parameters = substr($_SERVER['QUERY_STRING'], strrpos($_SERVER['QUERY_STRING'], 'c=') + 2, 3);
            //取得目前網址(不擷取目前幣值)
            $current_URL = "https://" . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], 'c='));

            //定義所有幣值
            $currency_All = Array("TWD", "VND", "MYR");

            //形成幣值下拉選擇
            $currency_html .= "<a class=\"nav-link dropdown-toggle\" href=\"currencyValueMenu\" id=\"currencyValueDropDown\" role=\"button\"
						   data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
							{$currency_parameters}
						</a>";
            $currency_html .= "<div class=\"dropdown-menu\" id=\"currencyValueMenu\">";
            for ($i = 0; $i < count($currency_All); $i++) {
                if ($currency_All[$i] == $currency_parameters) {
                    $currency_html .= "<a class=\"dropdown-item active\" href=\"{$current_URL}c={$currency_parameters}\">{$currency_parameters}</a>";
                } else {
                    $currency_html .= "<a class=\"dropdown-item\" href=\"{$current_URL}c={$currency_All[$i]}\">{$currency_All[$i]}</a>";
                }
            }
            $currency_html .= "</div>";
        }


        //檢查是否要形成幣值下拉選擇元素
        $currency_drop_down = "";

        if ($currency_parameters != "") {
            $currency_drop_down = "<li class=\"nav-item dropdown option\">
						{$currency_html}
					</li>";
        }
        /*幣值處理 ------------------------------------------------------------end*/

        if ($this->check_teacherData()) {
            $become_teacher_link = null;
            $course_management_link = '<li class="nav-item option"><a class="nav-link" style="float:left;" href="' . $url . 'course_management/index/type_live_course">課程管理</a></li>';
        } else {
            $become_teacher_link = null;
            $course_management_link = null;
        }

        if ($bo) {
            $GLOBALS['contact'] = $this->getContact(1); //取得訊息資料
            $HTML = "<!--    right-icon-group    -->
                <div class=\"row right-bar\">
				<ul class=\"navbar-nav right-icon-group\" id='right-icon'>
					<li class=\"nav-item option\"> <!-- 通知 -->
					    <span class=\"new_notify_hint\" id=\"new_notify_hint\"></span>
						<i><i class=\"nav-link fa fa-bell\" id=\"notify_icon\" href=\"#notify_container\"></i></i>
					</li> 
					<li class=\"nav-item option op_css\"> <!-- 訊息 -->
					    <span class=\"new_message_hint\" id=\"new_message_hint\"></span>
						<i><i class=\"nav-link fa fa-envelope\" id=\"contact_icon\" href=\"#contact_container\"></i></i>
					</li> 
					<li class=\"nav-item option\"> <!-- 購物車 -->
						<i class='fas'><a class=\"nav-link fa fa-shopping-cart\" href=\"{$url}shopping_cart\"></a></i>
					</li> 
					<li class=\"nav-item head_sticker option\"> <!-- 頭像 -->
						<a class=\"nav-link\" href=\"#personal_div\"><img
								src=\"{$url}{$photoPatch}\" id=\"personal_icon\"></a>
					</li> 
				</ul>

				<!--    right-item-group    -->
				<ul class=\"navbar-nav right-item-group col-sm\" id='right-item'>
                    <li class=\"nav-item d-flex justify-content-center align-items-center\" id=\"nav-search\">
                        <div class=\"search_area d-flex\">
                            <input type=\"text\" id=\"search_text\" autocomplete=\"off\" placeholder=\"探索課程\" class=\"el-input__inner\">
                            <i class=\"fa fa-search\" onclick=\"search_course('{$url}')\"></i>
                        </div>
                    </li>
					<li class=\"nav-item dropdown option\">
						<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\"
						   data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">繁體中文(台灣)</a>
						<div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown1\">
							<a class=\"dropdown-item\" href=\"#\">繁體中文(台灣)</a>
							<a class=\"dropdown-item\" href=\"#\">English</a>
						</div>
					</li>
					<!--幣值下拉-->
					{$currency_drop_down}
					<!--目前時間-->
					<li class=\"nav-item option\">
						<div class=\"nav-link\" id=\"showbox\"></div>
					</li>
					{$become_teacher_link}
					{$course_management_link}
	

				</ul></div>";
        } else {
            $HTML = "<ul class=\"navbar-nav row home-right-item-block \">
                <li class=\"nav-item dropdown option\">
                    <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\"
                       data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        繁體中文(台灣)
                    </a>
                    <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown1\">
                        <a class=\"dropdown-item\" href=\"#\">繁體中文(台灣)</a>
							<a class=\"dropdown-item\" href=\"#\">English</a>
                    </div>
                </li>
                <!--幣值下拉-->
					{$currency_drop_down}
                <!--目前時間-->
                <li class=\"nav-item option\">
                    <div class=\"nav-link\" id=\"showbox\"></div>
                </li>

                <li class=\"nav-item option\">
                    <a class=\"nav-link\" href=\"#\">常見問題</a>
                </li>
                <li class=\"nav-item option\" data-toggle=\"modal\"
                    data-target=\"#signIn_window\">
                    <a class=\"nav-link\" href=\"#\">登入</a>
                </li>
                <li class=\"nav-item option\" data-toggle=\"modal\"
                    data-target=\"#registered_window\">
                    <a class=\"nav-link\" href=\"#\">註冊</a>
                </li>
            </ul>";
        }

        return $HTML;
    }

    public function getHeaderRightIconMenu($url = '', $empty = '')
    {
        $HTML = "";
        if ($empty) {
            $HTML = "	<!--	鈴鐺Menu	-->
			<div class=\"collapse p-7\" id=\"notify_container\">
				<div class=\"notice-empty col-sm-12\" style=\"display: none\">
					<span class=\"notice-empty-icon\">
						<i class=\"notice-empty-icon__inner fa fa-bell-slash\"></i>
					</span>
					<div class=\"notice-empty-content mt-4\">
						<h1>目前尚無通知</h1>
					</div>
				</div>
			</div>";
        } else {
            if (isset($_SESSION['Mid'])) {
                $notice = $this->getNotice(0); //取得鈴鐺通知資料

                $HTML .= "	<!--	鈴鐺Menu外部	-->
			<div class=\"collapse p-7 notify_container\" id=\"notify_container\">
				<div class=\"notice-course\">
					<h3 class='menu_title'>通知</h3>
					<div class=\"notice_area\" id=\"notice_area\">";
                $mainHaveRead = @($this->db->select('haveRead')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row())->haveRead;

                for ($i = 0, $j = 0; $i < count($notice); $i++, $j++) {

                    if ($i > 0) {
                        if ($notice[$i - 1]->id == $notice[$i]->id) { //上次資料與這次資料相同時就跳過這次形成
                            $j--;
                            continue;
                        }
                    }

                    $notice[$i]->messageTitle = ($notice[$i]->messageTitle == NULL) ? "無標題" : $notice[$i]->messageTitle;
                    if (preg_match("/{$notice[$i]->id}/i", $mainHaveRead))
                        $haveRead = 'notice-course__inner_haveRead';
                    else
                        $haveRead = 'notice-course__inner';

                    $photo_path = "";
                    if ($notice[$i]->photo == "管理員") {
                        $photo_path = "{$url}resource/image/share/admin.jpg?value=" . uniqid();
                    } else {
                        //jpg判斷
                        if (file_exists("resource/image/teacher/live/{$notice[$i]->photo}.jpg")) {
                            $photo_path = "{$url}resource/image/teacher/live/{$notice[$i]->photo}.jpg?value=" . uniqid();
                        } else if (file_exists("resource/image/teacher/film/{$notice[$i]->photo}.jpg")) {
                            $photo_path = "{$url}resource/image/teacher/film/{$notice[$i]->photo}.jpg?value=" . uniqid();
                        } else if (file_exists("resource/image/teacher/fundraisingCourse/{$notice[$i]->photo}.jpg")) {
                            $photo_path = "{$url}resource/image/teacher/fundraisingCourse/{$notice[$i]->photo}.jpg?value=" . uniqid();
                        } //png判斷
                        else if (file_exists("resource/image/teacher/live/{$notice[$i]->photo}.png")) {
                            $photo_path = "{$url}resource/image/teacher/live/{$notice[$i]->photo}.png?value=" . uniqid();
                        } else if (file_exists("resource/image/teacher/film/{$notice[$i]->photo}.png")) {
                            $photo_path = "{$url}resource/image/teacher/film/{$notice[$i]->photo}.png?value=" . uniqid();
                        } else if (file_exists("resource/image/teacher/fundraisingCourse/{$notice[$i]->photo}.png")) {
                            $photo_path = "{$url}resource/image/teacher/fundraisingCourse/{$notice[$i]->photo}.png?value=" . uniqid();
                        }
                    }


                    $HTML .= "	<!--	鈴鐺Menu內部	-->
					<div class=\"{$haveRead} d-flex\" id=\"notify_detail{$j}\" onclick=\"notify_detail({$j},{$notice[$i]->id});\">
						<img class=\"course__inner-img\" alt=\"course image\" src=\"{$photo_path}\">
						<span class=\"course__inner-content row\">
							<span class=\"col-sm-12\">{$notice[$i]->messageTitle}</span>
							<span class=\"col-sm-12\" id=\"notify_date{$j}\">{$this->getTimeDifference($notice[$i]->date)}</span>
							<span id=\"notify_actual_date{$j}\" style=\"display: none\">{$notice[$i]->date}</span>
						</span>
					</div>";
                }
                $HTML .= "</div></div></div>"; //鈴鐺Menu 外部結尾

                $HTML .= "	<!--	訊息Menu外部	-->
			<div class=\"collapse p-7 contact_container\" id=\"contact_container\">
				<div class=\"contact\">
				<h3 class='menu_title'>訊息</h3>
				<div class=\"contact_area\" id=\"contact_area\">";
                $contactIndex = 1;

                if (count($GLOBALS['contact']['message']) == 2) {
                    if ($GLOBALS['contact']['message'][0]->who_say == 'M' or $GLOBALS['contact']['message'][0]->have_read == "1")
                        $haveRead = 'contact__inner_haveRead';
                    else
                        $haveRead = 'contact__inner';


                    $HTML .= "	<!--	訊息Menu內部	-->
					<div class=\"{$haveRead} d-flex\" id=\"contact_detail0\" data-admin__chat=\"_0,{$_SESSION['Mid']},{$GLOBALS['contact']['message'][0]->id}\" onclick=\"buildLiveChatToAdmin(this);\">
						<img class=\"contact__inner-img\" alt=\"sender image\" src=\"{$url}resource/image/share/{$GLOBALS['contact']['message'][0]->photo} ?value=" . uniqid() . "\">
						<span class=\"contact__inner-content row\">
							<span class=\"col-sm-12\">{$GLOBALS['contact']['message'][0]->identity} {$GLOBALS['contact']['message'][0]->name}</span>
							<span class=\"col-sm-12\">{$GLOBALS['contact']['message'][0]->message}</span>
							<span class=\"col-sm-12\" style='color: #929292'>{$this->getTimeDifference($GLOBALS['contact']['message'][0]->date)}</span>
						</span>
					</div>";
                } else
                    $contactIndex = 0;

                for ($i = 0, $j = 0; $i < count($GLOBALS['contact']["message"][$contactIndex]); $i++, $j++) {

                    if ($i > 0) {
                        //上次資料與這次資料相同時就跳過這次形成
                        if ($GLOBALS['contact']['message'][$contactIndex][$i - 1]->id == $GLOBALS['contact']['message'][$contactIndex][$i]->id) {
                            $j--;
                            continue;
                        }
                    }

                    if ($GLOBALS['contact']['message'][$contactIndex][$i]->have_read == '1' || $GLOBALS['contact']['message'][$contactIndex][$i]->who_say == '1')
                        $haveRead = 'contact__inner_haveRead';
                    else
                        $haveRead = 'contact__inner';

                    //沒設大頭照的話 設為 預設頭貼
                    $photo = $GLOBALS['contact']['message'][$contactIndex][$i]->photo != "" ? $GLOBALS['contact']['message'][$contactIndex][$i]->photo : "noPhoto.jpg";

                    $HTML .= "	<!--	訊息Menu內部	-->
					<div class=\"{$haveRead} d-flex\" id=\"contact_detail" . ($j + 1) . "\" onclick=\"contact_detail('" . ($j + 1) . "','{$GLOBALS['contact']['message'][$contactIndex][$i]->identity}','{$GLOBALS['contact']['message'][$contactIndex][$i]->id}','{$GLOBALS['contact']['message'][$contactIndex][$i]->name}','{$GLOBALS['contact']['message'][$contactIndex][$i]->id2}','{$GLOBALS['contact']['message'][$contactIndex][$i]->id3}');\">
						<img class=\"contact__inner-img\" alt=\"sender image\" src=\"{$url}resource/image/student/photo/" . $photo . "?value=" . uniqid() . "\">
						<span class=\"contact__inner-content row\">
							<span class=\"col-sm-12\">{$GLOBALS['contact']['message'][$contactIndex][$i]->identity} {$GLOBALS['contact']['message'][$contactIndex][$i]->name}</span>
							<span class=\"col-sm-12\">{$GLOBALS['contact']['message'][$contactIndex][$i]->message}</span>
							<span class=\"col-sm-12\" style='color: #929292'>{$this->getTimeDifference($GLOBALS['contact']['message'][$contactIndex][$i]->date)}</span>
							<span class=\"col-sm-12\" id=\"contact_actual_date" . ($j + 1) . "\" style='display: none'>{$GLOBALS['contact']['message'][$contactIndex][$i]->date}</span>
						</span>
					</div>";

                }

                $HTML .= "</div></div></div>"; //訊息Menu 外部結尾
                $HTML .= "<!--    管理員聯繫視窗 Start   -->
                            <div class=\"live-chat__container\" id=\"liveChat\">
                                <div class=\"live-chat__main\">
                                    <div class=\"chat__header\">
                                        <div class=\"chat__header-name\">
                                            <div class=\"header-name__text\">管理員</div>
                                        </div>
                                        <div class=\"chat__header-close\" onclick=\"toggleLiveChat()\">
                                            <i class=\"fa fa-times\"></i>
                                        </div>
                                    </div>
                                    <div class=\"chat__body\">
                                        <div class=\"chat__body-inner\" id=\"chatBodyInner\">
                                        </div>
                                    </div>
                                    <div class=\"chat__footer\">
                                        <div class=\"chat__footer-sendMessage-box d-flex align-items-center\">
                                            <textarea class=\"sendMessage__input form-control\" id=\"sendMessageInput\" placeholder=\"請輸入訊息...\"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--    管理員聯繫視窗 End   -->";

                if (isset($_SESSION['front_end_admin'])) {
                    $HTML .= "<span class='dy-none' id='identify'>admin</span>";
                }
                if (isset($_SESSION['Tid'])) {
                    if ($this->getTeacherStatus() == 1)
                        $HTML .= "<span class='dy-none' id='identify'>teacher</span>";
                    else {
                        $HTML .= "<span class='dy-none' id='identify'>student</span>";
                    }
                } else {
                    $HTML .= "<span class='dy-none' id='identify'>student</span>";
                }

            }
        }

        return $HTML;
    }

    public function getTimeDifference($date = '')
    {
        $second1 = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($date)));
        $return = '';
        $time1 = floor($second1 / 86400);
        if ($time1 > 0)
            return $date;
        $time2 = floor(($second1 % 86400) / 3600);
        $time3 = floor((($second1 % 86400) % 3600) / 60);
        $time4 = floor((($second1 % 86400) % 3600) % 60);
        if ($time1 > 0)
            $return .= "{$time1}天";
        if ($time2 > 0)
            $return .= "{$time2}時";
        if ($time3 > 0)
            $return .= "{$time3}分";

        $return .= "{$time4}秒前";
        return $return;
    }

    public function checkOneLogin()
    {
        $ip = $this->input->ip_address();
        if ($this->db->select('IP')->from('member')->where('m_id', @$_SESSION['Mid'])->where('IP !=', $ip)->get()->num_rows()) {
            session_destroy();
            redirect(base_url('home?type=1'));
        }
    }

    public function getTeacherStatus()
    {
        $result = $this->db->select('teacherStatus')->from('main')->where('t_id', @$_SESSION['Tid'])->get()->result();

        return @$result[0]->teacherStatus;
    }

    function getNotice($index = 0)
    {
        $emailDate = ($this->db->select('email_date')->from('member')->where('m_id', $_SESSION['Mid'])->get()->row())->email_date;
        if ($emailDate == null)
            $emailDate = date('Y-m-d H:i:s');

        if (isset($_SESSION['Tid'])) {
            $where = "nr_date > '{$emailDate}' AND (((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1 OR nr_noticeObject = 2)) OR nr_specificObject = '{$_SESSION['Mid']}' OR nr_specificObject = '{$_SESSION['Tid']}')";
            return $this->db->select("nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date, IF(nr_sendIdentity = 'A', '管理員', nr_specificObject) AS photo")
                ->from('notice_record')
                ->where($where)
                ->join('shoppingcart AS S', "notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id", 'left')
                ->or_where('S.m_id', $_SESSION['Mid'])
                ->or_where('S.t_id', $_SESSION['Tid'])
                ->join('fundraising_course_list', 'notice_record.nr_specificObject = fundraising_course_list.fc_id', 'left')
                ->join('fundraising_course', 'notice_record.nr_specificObject = fundraising_course.fc_id', 'left')
                ->or_where('fundraising_course_list.m_id', $_SESSION['Mid'])
                ->or_where('fundraising_course.t_id', $_SESSION['Tid'])
                ->group_by(array('messageTitle', 'date'))
                ->order_by('nr_date', 'DESC')
                ->limit(25, $index * 25)
                ->get()->result();
        } else {
            $where = "((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1)) OR nr_specificObject = '{$_SESSION['Mid']}'";
            return $this->db->select("nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date, IF(nr_sendIdentity = 'A', '管理員', nr_specificObject) AS photo")
                ->from('notice_record')
                ->where($where)
                ->where('nr_date >', $emailDate)
                ->join('shoppingcart AS S', "(notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id)", 'left')
                ->where('S.m_id', $_SESSION['Mid'])
                ->join('fundraising_course_list', 'notice_record.nr_specificObject = fundraising_course_list.fc_id', 'left')
                ->or_where('fundraising_course_list.m_id', $_SESSION['Mid'])
                ->group_by(array('messageTitle', 'date'))
                ->order_by('nr_date', 'DESC')
                ->limit(25, $index * 25)
                ->get()->result();
        }
    }

    function get_old_Notice()
    {
        $data = $this->input->post();
        $mainHaveRead = @($this->db->select('haveRead')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row())->haveRead;

        $emailDate = @($this->db->select('email_date')->from('member')->where('m_id', $_SESSION['Mid'])->get()->row())->email_date;
        if ($emailDate == null)
            $emailDate = date('Y-m-d H:i:s');

        if (isset($_SESSION['Tid'])) {
            $where = "nr_date > '{$emailDate}' AND (((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1 OR nr_noticeObject = 2)) OR nr_specificObject = '{$_SESSION['Mid']}' OR nr_specificObject = '{$_SESSION['Tid']}')";
            $result = $this->db->select("nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date, IF(nr_sendIdentity = 'A', '管理員', nr_specificObject) AS photo")
                ->from('notice_record')
                ->where($where)
                ->join('shoppingcart AS S', "notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id", 'left')
                ->or_where('S.m_id', $_SESSION['Mid'])
                ->or_where('S.t_id', $_SESSION['Tid'])
                ->join('fundraising_course_list', 'notice_record.nr_specificObject = fundraising_course_list.fc_id', 'left')
                ->join('fundraising_course', 'notice_record.nr_specificObject = fundraising_course.fc_id', 'left')
                ->or_where('fundraising_course_list.m_id', $_SESSION['Mid'])
                ->or_where('fundraising_course.t_id', $_SESSION['Tid'])
                ->group_by(array('messageTitle', 'date'))
                ->order_by('nr_date', 'DESC')
                ->limit(25, $data['index'] * 25)
                ->get()->result();

            for ($i = 0; $i < count($result); $i++) {
                if (preg_match("/{$result[$i]->id}/i", $mainHaveRead))
                    $result[$i]->haveRead = 'notice-course__inner_haveRead ';
                else
                    $result[$i]->haveRead = 'notice-course__inner ';

                $result[$i]->actual_date = $result[$i]->date;
                $result[$i]->date = $this->getTimeDifference($result[$i]->date);

                $photo_path = "";
                if ($result[$i]->photo == "管理員") {
                    $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/share/admin.jpg?value=" . uniqid();
                } else {
                    //jpg判斷
                    if (file_exists("resource/image/teacher/live/{$result[$i]->photo}.jpg")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/live/{$result[$i]->photo}.jpg?value=" . uniqid();
                    } else if (file_exists("resource/image/teacher/film/{$result[$i]->photo}.jpg")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/film/{$result[$i]->photo}.jpg?value=" . uniqid();
                    } else if (file_exists("resource/image/teacher/fundraisingCourse/{$result[$i]->photo}.jpg")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/fundraisingCourse/{$result[$i]->photo}.jpg?value=" . uniqid();
                    } //png判斷
                    else if (file_exists("resource/image/teacher/live/{$result[$i]->photo}.png")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/live/{$result[$i]->photo}.png?value=" . uniqid();
                    } else if (file_exists("resource/image/teacher/film/{$result[$i]->photo}.png")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/film/{$result[$i]->photo}.png?value=" . uniqid();
                    } else if (file_exists("resource/image/teacher/fundraisingCourse/{$result[$i]->photo}.png")) {
                        $photo_path = "https://ajcode.tk/teaching_platform_dev/resource/image/teacher/fundraisingCourse/{$result[$i]->photo}.png?value=" . uniqid();
                    }
                }

                $result[$i]->photo = $photo_path;
            }

            echo json_encode($result);
        } else {
            $where = "((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1)) OR nr_specificObject = '{$_SESSION['Mid']}'";
            $result = $this->db->select("nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date, IF(nr_sendIdentity = 'A', '管理員', nr_specificObject) AS photo")
                ->from('notice_record')
                ->where($where)
                ->where('nr_date >', $emailDate)
                ->join('shoppingcart AS S', "(notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id)", 'left')
                ->where('S.m_id', $_SESSION['Mid'])
                ->join('fundraising_course_list', 'notice_record.nr_specificObject = fundraising_course_list.fc_id', 'left')
                ->or_where('fundraising_course_list.m_id', $_SESSION['Mid'])
                ->group_by(array('messageTitle', 'date'))
                ->order_by('nr_date', 'DESC')
                ->limit(25, $data['index'] * 25)
                ->get()->result();

            for ($i = 0; $i < count($result); $i++) {
                if (preg_match("/{$result[$i]->id}/i", $mainHaveRead))
                    $result[$i]->haveRead = 'notice-course__inner_haveRead ';
                else
                    $result[$i]->haveRead = 'notice-course__inner ';

                $result[$i]->actual_date = $result[$i]->date;
                $result[$i]->date = $this->getTimeDifference($result[$i]->date);
            }

            echo json_encode($result);
        }
    }

    public function continuallyUpdated()
    {
        set_time_limit(0);//無限請求超時時間
        $i = 0;
        $data = $this->input->post();
        $return = array();
        while (true) {
            usleep(500000);//0.5秒
            $i += 1;
            $date = date("Y-m-d h:i:s");
            $date = date("Y-m-d h:i:s", strtotime("{$date} -1 second"));
            if (isset($_SESSION['Tid'])) {
                $where = "((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1 OR nr_noticeObject = 2)) OR nr_specificObject = '{$_SESSION['Mid']}' OR nr_specificObject = '{$_SESSION['Tid']}'";
                $return['notice'] = $this->db->select('nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date')
                    ->from('notice_record')
                    ->where('nr_date >', $date)
                    ->where($where)
                    ->join('shoppingcart AS S', "notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id", 'left')
                    ->or_where('S.m_id', $_SESSION['Mid'])
                    ->or_where('S.t_id', $_SESSION['Tid'])
                    ->get()->row();

                $return['message'] = $this->db->select("cw_message AS message, cw_date AS date, IF(cw_TID = 'A', '', IF(cw_MID = '{$_SESSION['Mid']}', t_name, m_name)) AS name,
             cw_id AS id, IF(cw_TID = 'A', '管理員', IF(cw_MID = '{$_SESSION['Mid']}', '老師', '學生')) AS identity, ROW_NUMBER() OVER (PARTITION BY cw_MID, cw_TID ORDER BY cw_date DESC) AS sn,
             cw_haveRead AS have_read")
                    ->from("contact_window")
                    ->where('cw_date >', $date)
                    ->group_start()
                    ->where('cw_TID', $_SESSION['Tid'])
                    ->join('member', 'member.m_id = cw_MID', 'left')
                    ->group_end()
                    ->or_group_start()
                    ->where('cw_MID', $_SESSION['Mid'])
                    ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                    ->group_end()
                    ->get_compiled_select();
                $return['message'] = $this->db->query("SELECT S1.* FROM({$return['message']}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT 0, 5 ")->result();

            } else {
                $where = "((nr_emailOrNotice = 0 OR nr_emailOrNotice = 2) AND (nr_noticeObject = 0 OR nr_noticeObject = 1)) OR nr_specificObject = '{$_SESSION['Mid']}'";
                $return['notice'] = $this->db->select('nr_id AS id, nr_messageTitle AS messageTitle, nr_date AS date')
                    ->from('notice_record')
                    ->where('nr_id >', $date)
                    ->where($where)
                    ->join('shoppingcart AS S', "(notice_record.nr_specificObject = S.l_id OR notice_record.nr_specificObject = S.cf_id)", 'inner')
                    ->where('S.m_id', $_SESSION['Mid'])
                    ->get()->row();

                $return['message'] = $this->db->select("cw_message AS message, cw_date AS date, IF(t_name = 'null', t_name, '') AS name, cw_id AS id, IF(cw_TID = 'A', '管理員', '老師') AS identity
            , ROW_NUMBER() OVER (PARTITION BY cw_MID ORDER BY cw_date DESC) AS sn, cw_haveRead AS have_read")
                    ->from('contact_window')
                    ->where('cw_date >', $date)
                    ->where('cw_MID', $_SESSION['Mid'])
                    ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                    ->get_compiled_select();
                $return['message'] = $this->db->query("SELECT S1.* FROM({$return['message']}) S1 where S1.sn = '1' LIMIT 0, 5")->result();
            }
            if ($return['notice'] != null || $return['message'] != null) {
                echo json_encode(array($return));
                exit();
            }
            if ($i == 120) {
                echo json_encode(array('static' => false, 'msg' => '無新資料'));
                exit();
            }
        }
    }

    public function getNoticeDetail()
    {
        $data = $this->input->post();
        $haveRead = ($this->db->select('haveRead')->from('main')->where('m_id', $_SESSION['Mid'])->get()->row())->haveRead;
        if (!preg_match("/{$data['id']}/i", $haveRead))
            $haveRead .= "{$data['id']}、";
        $this->db->where('m_id', $_SESSION['Mid'])->update('main', array('haveRead' => $haveRead));

        echo json_encode($this->db->select('nr_messageTitle AS messageTitle, nr_sendMessage AS message, nr_date AS date')->from('notice_record')->where('nr_id', $data['id'])->get()->row());
    }

    public function getContact($index = 1)
    {
        if (isset($_SESSION['Tid']))
            $who_say = "IF(cw_TID = '{$_SESSION['Tid']}', IF(who_say = 'T', '1', '0'), IF(who_say = 'S', '1', '0')) AS who_say, IF(cw_TID = '{$_SESSION['Tid']}', cw_MID, cw_TID) AS id2, id AS id3";
        else
            $who_say = "IF(who_say = 'S', '0', '1') AS who_say, IF(cw_TID = '" . @$_SESSION['Tid'] . "', cw_MID, cw_TID) AS id2, id AS id3";

        $start = ($index - 1) * 25;
        $end = $index * 25;
        $returnData['message'] = $this->db->select('acw_id AS id, acw_message AS message, acw_date AS date, "" AS name, "管理員" AS identity,
        ROW_NUMBER() OVER (PARTITION BY acw_MID ORDER BY acw_date DESC) AS sn, acw_haveRead AS have_read, "admin.jpg" AS photo, who_say')
            ->from("admin_contact_window")
            ->where('acw_MID', $_SESSION['Mid'])
            ->get_compiled_select();
        $returnData['message'] = $this->db->query("SELECT S1.* FROM({$returnData['message']}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT $start, $end")->result();

        if (isset($_SESSION['Tid'])) {
            $select = $this->db->select("cw_message AS message, cw_date AS date, IF(cw_TID = 'A', '', IF(cw_MID = '{$_SESSION['Mid']}', t_name, m_name)) AS name,
             cw_id AS id, IF(cw_TID = 'A', '管理員', IF(cw_MID = '{$_SESSION['Mid']}', '老師', '學生')) AS identity, ROW_NUMBER() OVER (PARTITION BY cw_MID, cw_TID ORDER BY cw_date DESC) AS sn,
             cw_haveRead AS have_read, IF(cw_MID = '{$_SESSION['Mid']}', t_photo, m_photo) AS photo, $who_say")
                ->from("contact_window")
                ->group_start()
                ->where('cw_TID', $_SESSION['Tid'])
                ->join('member', 'member.m_id = cw_MID', 'left')
                ->group_end()
                ->or_group_start()
                ->where('cw_MID', $_SESSION['Mid'])
                ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                ->group_end()
                ->get_compiled_select();
            $returnData['message'][] = $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT $start, $end")->result();
        } elseif (isset($_SESSION['Mid'])) {
            $select = $this->db->select("cw_message AS message, cw_date AS date, IF(t_name = 'null', t_name, '') AS name, cw_id AS id, IF(cw_TID = 'A', '管理員', '老師') AS identity
            , ROW_NUMBER() OVER (PARTITION BY cw_MID ORDER BY cw_date DESC) AS sn, cw_haveRead AS have_read, m_photo AS photo, $who_say")
                ->from('contact_window')
                ->where('cw_MID', $_SESSION['Mid'])
                ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                ->join('main', 'teacher.t_id = main.t_id', 'left')
                ->join('member', 'member.m_id = main.m_id', 'left')
                ->get_compiled_select();
            $returnData['message'][] = $this->db->query("SELECT S1.* FROM({$select}) S1 where S1.sn = '1' LIMIT $start, $end")->result();
        }

        return $returnData;
    }

    function get_old_Contact()
    {
        $data = $this->input->post();

        if (isset($_SESSION['Tid']))
            $who_say = "IF(cw_TID = '{$_SESSION['Tid']}', IF(who_say = 'T', '1', '0'), IF(who_say = 'S', '1', '0')) AS who_say, IF(cw_TID = '{$_SESSION['Tid']}', cw_MID, cw_TID) AS id2, id AS id3";
        else
            $who_say = "IF(who_say = 'S', '0', '1') AS who_say, IF(cw_TID = '" . @$_SESSION['Tid'] . "', cw_MID, cw_TID) AS id2, id AS id3";

        $start = ($data['index'] - 1) * 25;
        $end = $data['index'] * 25;

        $returnData['message'] = $this->db->select('acw_id AS id, acw_message AS message, acw_date AS date, "" AS name, "管理員" AS identity,
        ROW_NUMBER() OVER (PARTITION BY acw_MID ORDER BY acw_date DESC) AS sn, acw_haveRead AS have_read, "admin.jpg" AS photo, who_say')
            ->from("admin_contact_window")
            ->where('acw_MID', $_SESSION['Mid'])
            ->get_compiled_select();
        $returnData['message'] = $this->db->query("SELECT S1.* FROM({$returnData['message']}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT $start, $end")->result();

        if (isset($_SESSION['Tid'])) {
            $select = $this->db->select("cw_message AS message, cw_date AS date, IF(cw_TID = 'A', '', IF(cw_MID = '{$_SESSION['Mid']}', t_name, m_name)) AS name,
             cw_id AS id, IF(cw_TID = 'A', '管理員', IF(cw_MID = '{$_SESSION['Mid']}', '老師', '學生')) AS identity, ROW_NUMBER() OVER (PARTITION BY cw_MID, cw_TID ORDER BY cw_date DESC) AS sn,
             cw_haveRead AS have_read, IF(cw_MID = '{$_SESSION['Mid']}', t_photo, m_photo) AS photo, $who_say")
                ->from("contact_window")
                ->group_start()
                ->where('cw_TID', $_SESSION['Tid'])
                ->join('member', 'member.m_id = cw_MID', 'left')
                ->group_end()
                ->or_group_start()
                ->where('cw_MID', $_SESSION['Mid'])
                ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                ->group_end()
                ->get_compiled_select();
            $returnData['message'][] = $this->db->query("SELECT S1.* FROM({$select}) AS S1 where S1.sn = '1' ORDER BY S1.date DESC LIMIT $start, $end")->result();
        } elseif (isset($_SESSION['Mid'])) {
            $select = $this->db->select("cw_message AS message, cw_date AS date, IF(t_name = 'null', t_name, '') AS name, cw_id AS id, IF(cw_TID = 'A', '管理員', '老師') AS identity
            , ROW_NUMBER() OVER (PARTITION BY cw_MID ORDER BY cw_date DESC) AS sn, cw_haveRead AS have_read, m_photo AS photo, $who_say")
                ->from('contact_window')
                ->where('cw_MID', $_SESSION['Mid'])
                ->join('teacher', 'teacher.t_id = cw_TID', 'left')
                ->join('main', 'teacher.t_id = main.t_id', 'left')
                ->join('member', 'member.m_id = main.m_id', 'left')
                ->get_compiled_select();
            $returnData['message'][] = $this->db->query("SELECT S1.* FROM({$select}) S1 where S1.sn = '1' LIMIT $start, $end")->result();
        }

        /*形成訊息區塊*/
        if (isset($returnData["message"][1])) {
            for ($i = 0; $i < count($returnData["message"][1]); $i++) {
                if ($returnData['message'][1][$i]->have_read == '1' || $returnData['message'][1][$i]->who_say == '1')
                    $returnData["message"][1][$i]->haveRead = 'contact__inner_haveRead ';
                else
                    $returnData["message"][1][$i]->haveRead = 'contact__inner ';

                //沒設大頭照的話 設為 預設頭貼
                $returnData["message"][1][$i]->photo = $returnData['message'][1][$i]->photo != "" ? $returnData['message'][1][$i]->photo : "noPhoto.jpg";

                $returnData["message"][1][$i]->date = $this->getTimeDifference($returnData["message"][1][$i]->date);
            }

            echo json_encode($returnData["message"][1]);
        } else {
            echo json_encode(array());
        }


    }

    public function getContactDetail()
    {
        $data = array();
        $Data = $this->input->post();

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }

        $returnData = array();
        $this->db->trans_begin();
        foreach ($data AS $temp) {
            if (isset($_SESSION['Tid']))
                $who_say = "IF(cw_TID = '{$_SESSION['Tid']}', IF(who_say = 'T', '1', '0'), IF(who_say = 'S', '1', '0')) AS who_say";
            else
                $who_say = "IF(who_say = 'S', '1', '0') AS who_say";

            $returnData[] = $this->db->select("cw_message AS message, cw_date AS date, cw_TID AS teacherID, cw_MID AS memberID, {$who_say}")
                ->from('contact_window')
                ->where('cw_id', $temp['id'])
                ->order_by('cw_date', 'DESC')
                ->limit(10, ($temp['index'] - 1) * 10)
                ->get()->result();

            $this->db->where('cw_id', $temp['id'])->where('cw_haveRead', '0')->update('contact_window', array('cw_haveRead' => '1'));
        }
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            echo json_encode($returnData);
        } else {
            $this->db->trans_rollback();
        }
    }

    public function getAdminContactDetail()
    {
        $data = $this->Form_security_processing($this->input->post());

        $returnData = array();
        $this->db->trans_begin();

        $returnData[] = $this->db->select("acw_message AS message, acw_date AS date, IF(who_say = 'A', '1', '0') AS who_say")
            ->from('admin_contact_window')
            ->where('acw_MID', $_SESSION['Mid'])
            ->order_by('acw_date', 'DESC')
            ->limit(10, $data['index'] * 10)
            ->get()->result();

        $this->db->where('acw_MID', $_SESSION['Mid'])->where('who_say', 'A')->where('acw_haveRead', '0')->update('admin_contact_window', array('acw_haveRead' => '1'));

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            echo json_encode($returnData);
        } else {
            $this->db->trans_rollback();
        }
    }

    public function getNewContactDetail()
    {
        $data = $this->Form_security_processing($this->input->post());
        set_time_limit(0);//無限請求超時時間
        $i = 0;
        if (isset($_SESSION['Tid']))
            $who_say = "IF(cw_TID = '{$_SESSION['Tid']}', IF(who_say = 'T', '1', '0'), IF(who_say = 'S', '1', '0')) AS who_say, id";
        else
            $who_say = "IF(who_say = 'S', '0', '1') AS who_say, id";
        while (true) {
            sleep(0.5);//0.5秒
            $i++;
            $returnData = $this->db->select("cw_message AS message, cw_date AS date, {$who_say}")
                ->from('contact_window')
                ->where('cw_id', $data['id'])
                ->order_by('cw_date', 'DESC')
                ->limit(1, 0)
                ->get_compiled_select();
            $returnData = $this->db->query("SELECT S1.* FROM({$returnData}) AS S1 WHERE S1.who_say = '0'")->result();
            if ($returnData != null) {
                if ($returnData[0]->id != $data['id2']) {
                    $this->db->where('cw_id', $data['id'])->where('cw_haveRead', '0')->update('contact_window', array('cw_haveRead' => '1'));
                    echo json_encode(array('status' => true, 'contact' => $returnData));
                    break;
                }
            }
            if ($i == 250) {
                echo json_encode(array('status' => false));
                break;
            }
        }

    }

    public function getNewAdminContactDetail()
    {
        $data = $this->Form_security_processing($this->input->post());
        set_time_limit(0);//無限請求超時時間
        $i = 0;

        while (true) {
            sleep(0.5);//0.5秒
            $i++;
            $returnData = $this->db->select("acw_id AS id, acw_message AS message, acw_date AS date, IF(who_say = 'A', '1', '0') AS who_say")
                ->from('admin_contact_window')
                ->where('acw_MID', $_SESSION['Mid'])
                ->order_by('acw_date', 'DESC')
                ->limit(1, 0)
                ->get_compiled_select();

            $returnData = $this->db->query("SELECT S1.* FROM({$returnData}) AS S1 WHERE S1.who_say = '1'")->result();


            if ($returnData != null) {
                if ($returnData[0]->id != $data['id']) {
                    $this->db->where('acw_id', $data['id'])->where('acw_haveRead', '0')->update('admin_contact_window', array('acw_haveRead' => '1'));
                    echo json_encode(array('status' => true, 'contact' => $returnData));
                    break;
                }
            }
            if ($i == 250) {
                echo json_encode(array('status' => false));
                break;
            }
        }

    }

    public function addContact_TtoS()
    {
        if (!isset($_SESSION['Tid'])) {
            echo json_encode(array('status' => false, 'msg' => '您不是老師，無法傳訊息給學生'));
            return;
        }
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '傳送訊息不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->checkStudentIdentity($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '無此學生資料，請重新嘗試'));
            return;
        }
        $insert = array(
            'who_say' => 'T',
            'cw_TID' => $_SESSION['Tid'],
            'cw_MID' => $data['id'],
            'cw_message' => $data['message'],
            'cw_date' => date("Y/m/d H:i:s"),
            'cw_haveRead' => '0',
        );
        if ($this->checkRepeatContact($insert) < 3) {
            if ($this->checkContinuousContact($insert) > 20) {
                echo json_encode(array('status' => false, 'msg' => '請放慢傳送訊息的速度'));
                return;
            }
            $cw_id = $this->db->select('cw_id')->from('contact_window')->where('cw_TID', $insert['cw_TID'])
                ->where('cw_MID', $insert['cw_MID'])->get()->row();
            if ($cw_id != null)
                $insert['cw_id'] = $cw_id->cw_id;
            else
                $insert['cw_id'] = uniqid();

            if ($this->db->insert('contact_window', $insert))
                echo json_encode(array('status' => true, 'msg' => '傳送訊息成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '傳送訊息失敗'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '請勿重複傳送訊息，或放慢傳送訊息的速度'));
        }
    }

    public function addContact_StoT()
    {
        $this->checkOneLogin();
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '傳送訊息不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if ($this->checkTeacherIdentity($data['id'])) {
            echo json_encode(array('status' => false, 'msg' => '無此老師資料，請重新嘗試'));
            return;
        }
        $insert = array(
            'who_say' => 'S',
            'cw_TID' => $data['id'],
            'cw_MID' => $_SESSION['Mid'],
            'cw_message' => $data['message'],
            'cw_date' => date("Y/m/d H:i:s"),
            'cw_haveRead' => '0',
        );
        if ($this->checkRepeatContact($insert) < 3) {
            if ($this->checkContinuousContact($insert) > 20) {
                echo json_encode(array('status' => false, 'msg' => '請放慢傳送訊息的速度'));
                return;
            }
            $cw_id = $this->db->select('cw_id, cw_TID')->from('contact_window')->where('cw_TID', $insert['cw_TID'])
                ->where('cw_MID', $insert['cw_MID'])->get()->row();
            if ($cw_id != null)
                $insert['cw_id'] = $cw_id->cw_id;
            else
                $insert['cw_id'] = uniqid();
            if ($this->db->insert('contact_window', $insert))
                echo json_encode(array('status' => true, 'msg' => '傳送訊息成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '傳送訊息失敗'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '請勿重複傳送訊息'));
        }
    }

    public function addAdminContact()
    {
        $this->checkOneLogin();
        if (isset($_SESSION['MId'])) {
            echo json_encode(array('status' => false, 'msg' => '您未登入，請先登入'));
            return;
        }
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '傳送訊息不可為空'),
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }

        $insert = array(
            'who_say' => 'M',
            'acw_MID' => $_SESSION['Mid'],
            'acw_message' => $data['message'],
            'acw_date' => date("Y/m/d H:i:s"),
            'acw_haveRead' => '0',
        );
        if ($this->checkRepeatAdminContact($insert) < 3) {
            if ($this->checkContinuousAdminContact($insert) > 20) {
                echo json_encode(array('status' => false, 'msg' => '請放慢傳送訊息的速度'));
                return;
            }

            if ($this->db->insert('admin_contact_window', $insert))
                echo json_encode(array('status' => true, 'msg' => '傳送訊息成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '傳送訊息失敗'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '請勿重複傳送訊息'));
        }
    }

    public function checkRepeatContact($data = '')
    {
        return $this->db->select('*')
            ->from('contact_window')
            ->where('cw_TID', $data['cw_TID'])
            ->where('cw_MID', $data['cw_MID'])
            ->where('cw_message', $data['cw_message'])
            ->like('cw_date', date("Y-m-d H:i"))
            ->get()->num_rows();
    }

    public function checkContinuousContact($data = '')
    {
        return $this->db->select('*')
            ->from('contact_window')
            ->where('cw_TID', $data['cw_TID'])
            ->where('cw_MID', $data['cw_MID'])
            ->like('cw_date', date("Y-m-d H:i"))
            ->get()->num_rows();
    }

    public function checkRepeatAdminContact($data = '')
    {
        return $this->db->select('*')
            ->from('admin_contact_window')
            ->where('acw_MID', $data['acw_MID'])
            ->where('acw_message', $data['acw_message'])
            ->like('acw_date', date("Y-m-d H:i"))
            ->get()->num_rows();
    }

    public function checkContinuousAdminContact($data = '')
    {
        return $this->db->select('*')
            ->from('admin_contact_window')
            ->where('acw_MID', $data['acw_MID'])
            ->like('acw_date', date("Y-m-d H:i"))
            ->get()->num_rows();
    }


}

?>
