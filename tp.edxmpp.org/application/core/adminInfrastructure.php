<?php

class adminInfrastructure extends CI_Controller
{

    //false=未登入 true=已登入
    private $login = false;

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->login = isset($_SESSION['admin_name']);
    }

    public function getlogin()
    {
        if ($_SERVER['PHP_SELF'] != "/teaching_platform_dev/index.php/TPManager") {
            if ($this->db->select('*')->from('admin')->where('account', 'test1')->where('status', '1')->get()->num_rows() == 1)
                redirect(base_url("error"));
        }
        if ($this->login == null)
            return false;
        else
            return true;
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

    public function sign_out()
    {
        unset($_SESSION['admin_name']);
        redirect(base_url('TPManager'));
    }

    public function getSideBar($url = '')
    {
        $sideBarContent = "
                <button onclick=\"javascript:location.href+='/sign_out'\" class=\"side_bar-btn btn btn-info sign_out\">登入者:平台管理員<br><b><span>登出</span></b></button>
                <button onclick=\"javascript:location.href='admin_management'\" class=\"side_bar-btn btn btn-dark\">老師審核</button>
                <button onclick=\"javascript:location.href='teams_account_issues'\" class=\"side_bar-btn btn btn-dark\">Teams帳號發放</button>
                <button onclick=\"javascript:location.href='member_management'\" class=\"side_bar-btn btn btn-dark\">會員管理</button>
                <button onclick=\"javascript:location.href='message_management'\" class=\"side_bar-btn btn btn-dark\">會員聯繫管理</button>
                <button onclick=\"javascript:location.href='notice_record'\" class=\"side_bar-btn btn btn-dark\">通知管理</button>
                <button onclick=\"javascript:location.href='report_management'\" class=\"side_bar-btn btn btn-dark\">檢舉管理</button>
                <button onclick=\"javascript:location.href='currency_conversion'\" class=\"side_bar-btn btn btn-dark\">貸幣轉換管理</button>
                <button onclick=\"javascript:location.href='course_options'\" class=\"side_bar-btn btn btn-dark\">課程選項管理</button>
                <button onclick=\"javascript:location.href='course_label'\" class=\"side_bar-btn btn btn-dark\">課程標籤管理</button>
                <button onclick=\"javascript:location.href='hand_outDiamonds'\" class=\"side_bar-btn btn btn-dark\">鑽石發放管理</button>
                <button onclick=\"javascript:location.href='hand_outGolds'\" class=\"side_bar-btn btn btn-dark\">金幣發放管理</button>
                <button onclick=\"javascript:location.href='payment_history'\" class=\"side_bar-btn btn btn-dark\">付款紀錄管理</button>
                <button onclick=\"javascript:location.href='teacher_salary_management'\" class=\"side_bar-btn btn btn-dark\">老師薪資管理</button>
        ";

        return $sideBarContent;
    }
}

?>