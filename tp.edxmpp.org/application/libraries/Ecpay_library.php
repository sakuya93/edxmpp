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
        require_once APPPATH . 'resource/ECPay.Payment.Integration.php';

        $obj = new ECPay_AllInOne();


        # 電子發票參數
        /*
        $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
        $obj->SendExtend['RelateNumber'] = "Test".time();
        $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
        $obj->SendExtend['CustomerPhone'] = '0911222333';
        $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
        $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
        $obj->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($obj->Send['Items'] as $info)
        {
           array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
             $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
        }
           $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
           $obj->SendExtend['DelayDay'] = '0';
           $obj->SendExtend['InvType'] = ECPay_InvType::General;
        */
        //產生訂單(auto submit至ECPay)，此步驟會將前述設定的所有參數都一併傳給綠界，並將客戶導到綠界的刷卡頁面
        return $obj;
    }
}