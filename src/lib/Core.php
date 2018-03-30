<?php
/**
 * Created by PhpStorm.
 * User: beller
 */

namespace Lib;


use Sabre\Xml\Writer;

class Core {

    /**
     * 获取随机字符串
     *
     * @author  beller
     * @param   int $length
     * @return  string
     */
    public function getNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function make_notify_url($function = '',$_sn = array()){
        $domain = 'https://'.env('PAYSUBDOMAIN').'.'.env('BASE_DOMAIN');
        return $domain.$function.implode("/",$_sn);

    }

    /**
     * 获取退款通知地址
     *
     * @author    beller
     * @return    string
     */
    public function get_refund_notify_url($_sn,$shop_id = 0)
    {
        $url = $this->make_notify_url('/cashier/feedbacknotify/',$_sn);
        return $url.'/_/'.$shop_id;
    }

    public function get_cashpay_notify_url($_sn,$shop_id = 0){
        $url = $this->make_notify_url('/cashier/cashpaynotify/',$_sn);
        //$url = url('cashier/cashpaynotify',$_sn);
        return $url.'/_/'.$shop_id;
    }

    /**
     * 获取通知地址
     *
     * @author    beller
     * @return    string
     */
    public function get_notify_url($_sn,$shop_id = 0)
    {
        $url = $this->make_notify_url('/cashier/paynotify/',$_sn);
        //$url = url('cashier/paynotify',$_sn);
        return $url.'/_/'.$shop_id;
    }

    public function get_test_notify_url($_sn,$shop_id = 0)
    {
        $url = $this->make_notify_url('/cashier/paynotifytest/',$_sn);
        //$url = url('cashier/paynotifytest',$_sn);
        return $url.'/_/'.$shop_id;
    }

    /**
     * 获取返回地址
     *
     * @author    beller
     * @return    string
     */
    public function get_return_url($shop_url,$_sn)
    {
        return  $shop_url.'/trade/return/'.$_sn;
    }

    /**
     * 参数过滤
     * @param $para
     * @return array
     */
    public function _para_filter($para) {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }


    /**
     * 格式化时间戳
     * @author beller
     * @param $format
     * @param null $time
     * @return bool|string
     */
    public  function local_date($format, $time = NULL)
    {
        if ($time === NULL)
        {
            $time = gmtime();
        }
        elseif ($time <= 0)
        {
            return '';
        }
        return date($format, $time);
    }

    /**
     * 解析返回内容
     * @param $str_text
     * @return mixed
     */
    /*public function _parse_response($str_text) {
        //以“&”字符切割字符串
        $para_split = explode('&',$str_text);
        //把切割后的字符串数组变成变量与数值组合的数组
        foreach ($para_split as $item) {
            //获得第一个=字符的位置
            $nPos = strpos($item,'=');
            //获得字符串长度
            $nLen = strlen($item);
            //获得变量名
            $key = substr($item,0,$nPos);
            //获得数值
            $value = substr($item,$nPos+1,$nLen-$nPos-1);
            //放入数组中
            $para_text[$key] = $value;
        }

        if( ! empty ($para_text['res_data'])) {
            //token从res_data中解析出来（也就是说res_data中已经包含token的内容）
            $xml = new \DOMDocument();
            $xml->loadXML($para_text['res_data']);
            $para_text['request_token'] = $xml->getElementsByTagName('request_token')->item(0)->nodeValue;
            return $para_text;

        }
        return $para_text;
    }

    public function  wirterXml($data){
        $writer = new Writer();
        $writer->openMemory();
        $writer->startElement('xml');
        $writer->write($data);
        $writer->endElement();
        return $writer->outputMemory();
    }

    public function libxml_nocdata($xmlstr){
        return (array)simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    public function _get_html($paras, $pay_sign, $return_url) {
        $html = '<script type="text/javascript">';
        $html .= 'function jsApiCall(){';
        $html .= 'WeixinJSBridge.invoke("getBrandWCPayRequest", {';
        $html .= '"appId" : "' . $paras['appId'] . '",';
        $html .= '"timeStamp" : "' . $paras['timeStamp'] . '",';
        $html .= '"nonceStr" : "' . $paras['nonceStr'] . '",';
        $html .= '"package" : "' . $paras['package'] . '",';
        $html .= '"signType" : "MD5",';
        $html .= '"paySign" : "' . $pay_sign . '"';
        $html .= '},function(res){';
        $html .= 'if(res.err_msg == "get_brand_wcpay_request:ok" ) {';
        $html .= 'window.location.href = "' . $return_url . '";';
        $html .= '} else if (res.err_msg == "access_control:not_allow") {';
        $html .= 'alert("微信支付未审核");';
        $html .= '} else {';
        $html .= '';
        $html .= '}';
        $html .= '})';
        $html .= '}';
        $html .= '</script>';
        return $html;
    }*/

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    public function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }

} 