<?php
/**
 * Created by PhpStorm.
 * User: beller
 */

namespace Lib;


use Sabre\Xml\Writer;

class Wxpay_lib {

    /**
     * 生成xml
     * @author beller
     * @param $data
     * @return xml
     */
    public function  wirterXml($data){
        $writer = new Writer();
        $writer->openMemory();
        $writer->startElement('xml');
        $writer->write($data);
        $writer->endElement();
        return $writer->outputMemory();
    }

    /**
     * xml转数组
     * @author beller
     * @param $xmlstr
     * @return array
     */
    public function libxml_nocdata($xmlstr){
        return (array)simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * @author beler
     * @param $paras
     * @param $pay_sign
     * @param $return_url
     * @return string
     */
    public function _get_html($paras, $pay_sign, $return_url) {
        $html = '<script type="text/javascript">';
        $html .= 'function onBridgeReady(){';
        $html .= 'WeixinJSBridge.invoke(';
        $html .= '"getBrandWCPayRequest", {';
        $html .= '"appId" : "' . $paras['appId'] . '",';
        $html .= '"timeStamp" : "' . $paras['timeStamp'] . '",';
        $html .= '"nonceStr" : "' . $paras['nonceStr'] . '",';
        $html .= '"package" : "' . $paras['package'] . '",';
        $html .= '"signType" : "MD5",';
        $html .= '"paySign" : "' . $pay_sign . '"';
        $html .= '}, function(res){';
        $html .= 'if(res.err_msg == "get_brand_wcpay_request:ok" ) {';
        $html .= 'window.location.href = "' . $return_url . '";';
        $html .= '}else{  }';
        $html .= '});';
        $html .= '}';
        $html .= 'if (typeof WeixinJSBridge == "undefined"){';
        $html .= 'if( document.addEventListener ){';
        $html .= 'document.addEventListener("WeixinJSBridgeReady", onBridgeReady, false);';
        $html .= '}else if (document.attachEvent){';
        $html .= 'document.attachEvent("WeixinJSBridgeReady", onBridgeReady);';
        $html .= 'document.attachEvent("onWeixinJSBridgeReady", onBridgeReady);';
        $html .= '}';
        $html .= '}else{';
        $html .= 'onBridgeReady();';
        $html .= '}';
        $html .= '</script>';
        return $html;
    }


} 