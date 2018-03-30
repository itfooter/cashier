<?php
/**
 * Created by PhpStorm.
 * @author:beller
 */

namespace TaoTui\Cashier;


use Illuminate\Support\Facades\Request;


class Wxpay {

    const WX_PAY_URL    = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    protected $_config;
    protected $http;
    protected $core;
    protected $wx_lib;

    public function __construct()
    {
        //$this->http = new Http();
        //$this->core = new Core();
        //$this->wx_lib = new Wxpay_lib();
    }

    public function setConfig($config){
        $this->_config = $config;
        return $this;
    }

    /**
     * app支付
     */
    public function app_build_form($order){
        return array(123);
        $is_facilitator = $order['is_facilitator'];
        $title = $order['order_title'];
        $order_type = isset($order['order_type']) && $order['order_type'] ? $order['order_type'] : 1;
        $shop_id = isset($order['shop_id']) && $order['shop_id'] ? $order['shop_id'] : 0;
        $notify_url   = $this->core->get_notify_url(array($order['payment_sn'],$order_type),$shop_id);
        $package_arr = array(
            'appid'            => $this->_config['app_id'],
            'mch_id'           => $this->_config['mchid'],
            'nonce_str'        => $this->core->getNonceStr(32),
            'body'             => $title,
            'out_trade_no'     => $order['payment_sn'],
            'total_fee'        => $order['amount'] * 100,
            'spbill_create_ip' => Request::getClientIp(),
            'notify_url'       => $notify_url,
            'trade_type'       => 'APP'
        );
        if($is_facilitator){
            $package_arr['appid']  = $this->_facilitator['facilitator_sub_appid'];
            $package_arr['mch_id'] = $this->_facilitator['facilitator_sub_mch_id'];

            unset($package_arr['openid']);
            $package_arr['sub_openid'] = $order['openid'];
            $package_arr['sub_appid'] = $this->_config['app_id'];
            $package_arr['sub_mch_id'] = $this->_config['mchid'];
        }
        ksort($package_arr);
        reset($package_arr);
        if($is_facilitator)
            $md5sign = strtoupper(md5(urldecode(http_build_query($package_arr)).'&key='.$this->_facilitator['facilitator_key']));
        else
            $md5sign = strtoupper(md5(urldecode(http_build_query($package_arr)).'&key='.$this->_config['key']));

        $package_arr['sign'] = $md5sign;
        $package_xml =  $this->wx_lib->wirterXml($package_arr);
        $response = $this->http->post(static::WX_PAY_URL,['body' => $package_xml]);
        $response = $this->wx_lib->libxml_nocdata($response);
        if($response['return_code'] == 'SUCCESS' && isset($response['result_code']) && $response['result_code'] == 'SUCCESS'){
            $time = time();
            $_app_package_arr = array(
                'appid'            => $this->_config['app_id'],
                'partnerid'           => $this->_config['mchid'],
                'prepayid'        => $response['prepay_id'],
                'package'             => 'Sign=WXPay',
                'noncestr'     => $response['nonce_str'],
                'timestamp'        => $time
            );
            ksort($_app_package_arr);
            reset($_app_package_arr);
            $response['timestamp'] = $time;
            $response['sign'] = strtoupper(md5(urldecode(http_build_query($_app_package_arr)).'&key='.$this->_config['key']));
            if(isset($order['return_url']) && $order['return_url']){
                $return_url = $order['return_url'];
            }else{
                $return_url = $this->core->get_return_url($order['shop_url'],$order['order_sn']);
            }
            $response['return_url'] = $return_url;
        }
        return $response;
    }

    public function postXmlSSLCurl($xml,$url,$pem=array(),$second=30)
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        //这里设置代理，如果有的话
        if (config('weixin.proxy_ip')) {
            curl_setopt($ch,CURLOPT_PROXY, config('weixin.proxy_ip'));
        }
        if (config('weixin.proxy_port')) {
            curl_setopt($ch,CURLOPT_PROXYPORT, config('weixin.proxy_port'));
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, PEM_UPLOAD_PATH.$pem['apiclient_cert']);
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, PEM_UPLOAD_PATH.$pem['apiclient_key']);
        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);

        curl_close($ch);
        return $data;

    }

    /**
     * 通知验证
     */
    public function verify_notify($post_data) {
        $post_data = $post_data ? : file_get_contents("php://input");
        $post_data = (array)simplexml_load_string($post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$post_data) {
            return false;
        }
        if (!isset($post_data['transaction_id'])) {
            return false;
        }

        return array(
            'payment_sn'  => $post_data['out_trade_no'],
            'trade_sn'    => $post_data['transaction_id'],
            'notify_data' => json_encode($post_data),
        );
    }

}
