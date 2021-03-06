<?php

namespace Omnipay\AlipayHk\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\AlipayHk\Helper;

/**
 * Class MobilePurchaseResponse
 *
 * @package Omnipay\AlipayHk\Message
 */
class MobilePurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return true;
    }


    public function isRedirect()
    {
        return true;
    }


    public function getRedirectUrl()
    {
        return false;
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return false;
    }


    public function getMessage()
    {
        return $this->data;
    }


    public function getRedirectHtml()
    {
        $data = $this->data;

        $fields = [
            'merchant_reference'  => $data['merchant_reference'],
            'currency'            => $data['currency'],
            'amount'              => $data['amount'],
            'customer_ip'         => $data['customer_ip'],
            'customer_first_name' => $data['customer_first_name'],
            'customer_last_name'  => $data['customer_last_name'],
            'customer_phone'      => $data['customer_phone'],
            'customer_email'      => $data['customer_email'],
//            'return_url'          => $data['return_url'] // IP地址不能作为回调地址
        ];

        $fields['sign'] = Helper::genHashValue($fields, $data['secret']);

        $html = <<<eot
<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8"/> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    
    <body onload="document.forms['payment'].submit();"> 
        <form method="POST" action="{$data['gateway_url']}" name="payment" accept-charset="utf-8">
eot;
        foreach($fields as $k => $v) {
            $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
        }

        $html .= <<<eot
        </form> 
    </body> 
</html>
eot;

        return $html;
    }
}
