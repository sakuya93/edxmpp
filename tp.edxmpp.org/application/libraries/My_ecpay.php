<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Ecpay
{
    public function __construct()
    {
        log_message('Debug', 'ECpay class is loaded.');
    }

    public function load()
    {
        require_once  'resource/ECPay.Payment.Integration.php';

        $obj = new ECPay_AllInOne();
        return $obj;
    }
}