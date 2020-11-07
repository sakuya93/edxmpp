<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Infrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("home_model", "Model", TRUE);
    }

    public function _remap($url = '', $type1 = '', $type2 = '')
    {
        $language = $type1;
        if ($language == null) {
            $language = strtolower(strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ','));
        }
        if ($language == 'zh') $language = 'zh-tw';
        $this->lang->load('Home', $language);
        $GLOBALS['viewLang'] = $this->lang->line('view');
        $GLOBALS['controllerLang'] = $this->lang->line('controller');
        if ($url == '' | $url == 'index')
            $this->index();
        elseif ($url == 'registered')
            $this->registered();
        elseif ($url == 'login')
            $this->login();
    }

    public function index($type = '')
    {
        $data = $this->input->get();
        $view = $GLOBALS['viewLang'];
        $view['type'] = $type;
        $view['classOption'] = $this->getClassOption();
        $view['blockingReason'] = $this->Model->getBlockingReason(@$data['m']);
        $this->load->view('home_view', $view);
        $this->load->view('window/home/recommend_window');
        $this->load->view('window/home/registered_window');
        $this->load->view('window/home/signIn_window');
        $this->load->view('window/hint_window');
    }

    public function registered($type = 'email')
    {
        $data = $this->Form_security_processing($this->input->post());
        if ($data['name'] == '' | $data['account'] == '' | $data['password'] == '' | $data['password_confirm'] == '') {
            echo json_encode(array('status' => $GLOBALS['controllerLang']['not_null']));
            return;
        }
        if ($data['password'] != $data['password_confirm']) {
            echo json_encode(array('status' => $GLOBALS['controllerLang']['password_confirm_error']));
            return;
        }
        $check = $this->Model->checkRegistered($data['account']);
        if (filter_var($data['account'], FILTER_VALIDATE_EMAIL)) {
            $accountKey = 'not null';
        }else $accountKey = '^[a-zA-Z]{1,}[a-zA-Z0-9]{7,15}';
        if ($check->type) {
            $config = array(
                0 => array('key' => 'not null', 'msg' => $GLOBALS['controllerLang']['name_not_null']),
                1 => array('key' => $accountKey, 'msg' => '帳號格式錯誤'),
                2 => array('key' => '^[a-zA-Z]{1,}[a-zA-Z0-9]{7,15}', 'msg' => $GLOBALS['controllerLang']['password_error']),
            );
            $Form_normalization = $this->Form_normalization($data, $config);
            if ($Form_normalization->type) {
                if ($type == 'email') {
                    $insert = array(
                        'm_id' => uniqid(),
                        'm_name' => $data['name'],
                        'm_account' => $data['account'],
                        'm_password' => sha1($data['password'])
                    );
                } else if ($type == 'GOOGLE') {
                    $insert = array(
                        'm_id' => uniqid(),
                        'm_name' => $data['name'],
                        'm_email' => $data['email'],
                    );
                }
                if ($this->Model->registered($insert))
                    echo json_encode(array('status' => $GLOBALS['controllerLang']['registration_success']));
            } else
                echo json_encode(array('status' => $Form_normalization->msg));
        } else
            echo json_encode(array('status' => $check->msg));

    }


    public function login()
    {
        $data = $this->Form_security_processing($this->input->post());
        $login = $this->Model->login($data, $this->input->ip_address());
        if (!$login->type) {
            echo json_encode(array('status' => $GLOBALS['controllerLang']['email_or_password_error']));
            return;
        } else {
            $_SESSION['user_name'] = $login->data->user_name;
            if ($login->data->Mid)
                $_SESSION['Mid'] = $login->data->Mid;
            if ($login->data->Tid)
                $_SESSION['Tid'] = $login->data->Tid;
            echo json_encode(array('status' => $GLOBALS['controllerLang']['sign_in_suceesfully'], 'url' => base_url('student')));
        }
    }
}
