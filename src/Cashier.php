<?php
namespace TaoTui\Cashier;

use TaoTui\Cashier\Lib\AlipayTradeAppPayRequest;
use TaoTui\Cashier\Lib\AopClient;

class Cashier
{
    const ALI_PAY_URL      = 'https://openapi.alipay.com/gateway.do';
    protected $config = [];
    protected $core;
    protected $ali_lib;
    public function __construct()
    {
    }

	//测试1.0.3
    public function pay($data,$config,$code){
        $this->config = $config;
        return $this->$code($data);
    }

    public function wxpay()
    {
        return array('wxpay');

    }

    /***
     * 支付宝提交付款
     */
    public function alipay($order,$config){
        /*$this->config = array(
            'app_id' =>  '2017070807680397',
            'private_key' => 'MIIEpQIBAAKCAQEAtmjYnQKApW2XM+g3AqDGEpcEYVWT/1y9s4BITFYuGRwmobjlXoQ3k43vJfcviMFYFYv89ryavZT+qzKcFaq5wCL5bV0GZi06ju2gDe/jam6lk0ncW8iu1ppYDVxpdje2nJ6rmoDNULZ0tBLZO0wgIUlHE9AHLpMY1EqNmI7z034Zm8BjM/9XJ+GvXNUupVX476LK9ijzH4d2JsUJeBv0epEHCqazErz6UaTgCyTvrKiOkfJpcKnJ1x4a5ByTiqbk4hI7F/DDNrommCcVBNexoyKMGdihOOPekACXaVQJJYEDSGol5pmElycSq/FKeT2JWomygxnEa2OuUewXq7pVoQIDAQABAoIBAQCCQYaFQDc7anGxuF4n+0TWG48eLm07yi8Q0kdOc7ABcE/J5m2sO8AwFweyVbxU7LQI1ukyDGZ3gMEG4WICOuLf6FmzFd2u8xdA30EPEsGTzCViczjvNfRb/OVpzRbmO0yktM2Xy+hRfakCuf7z0B2Vh21BIakvQg1V81L/oP1OhSSolZDHkXnNZ3d6Ukgihi7owpe675OFi0hS11k/55Hq+WM7EZ/HDYZerE6YWJVkJymbA5UR8jTHE+sxX36ke5BKlAe9M9ji8biqLSqlJVSAlfAPP5cF+a0oIPoradmZ4aDB/76JnACfA8g7PymqVlf1CSmt5PS8X2JdrPOmQWNRAoGBAO1oy/GxyRejie6KrpA24yYszjUdjMs9Bo8cN1jM7dPPxQjysa1g1ZzJQxPW8JW1PXA5nbD+pZ4Kc1wSIvS0ieFA4Kce/mK0RucWZEE01hVE6BlhrMJecB4FRkrkaPfxZyH6EexD4IJz7Mpzr8xAOboaWUAta5XuyNEvZm0k4FSFAoGBAMSxgFS1ESWtJ4zo602Pn6H//tcIGcEuWrtuncAIeFIomVDDIw9waxdLVLNWmaOeZLE/EYpci1MsJUKRhXoe2XgntxIzmb4pG4ZKtx9ETCZTuR45xJHaBvGPu3OUbRgFVy5gtJH2B+otkziFJfesxRCS5eZ3fmP0x3a7emroPMVtAoGBAN6OmGmabdZgNATQMzb0jVqTNDgs3HkGW2i7XdM8QVOH+cEaqp/LIFVbgytNGrptbgwPpxlujtmqGFzej7BwsrTcef2RG45yS+mEXzWVj3/e2eo5W5fWqioqbav1lcWhozoB0N15ADWq8PcVHl9Ocg9ZdgOo0Kt8bvtTASUeJcJ1AoGAKephnKuRYeQKf57LNhYbQJybJBvX0TEdqL+j4l5rtaZtNaa/+/RK/gXRoP4dc563eoYkKMYb8rd/oTX9qc6VLWPZQYm5g6qi8PVPHNyjFa/VzTkmSKabwDKPEcxZizroHzwAfvJdMWxQ6USGmMc6n5OOZpSkXIy3TPga2FlxSekCgYEA6TZWS/r/HRI4stOyIwOyF+n3hVzeJc+asLUITQ9VscapxPeYHaIfrnUjXkg8OEbI5Ry2Nsj62a9RRUx+yCCFwFJ2Ih8lSeQ4FwXwVXWbRxE7VW/zAREAvQyR9Qc9NcP1WAZvHVsu+bslWUyUAICPnvaR68KyMojU4jKfD24QbVs=',
            'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk2WPKPJIgL58COWAZEP4ocJVeB0v2vmHtQm6UdnbYq1wwHfMkKgepvq/s2d0D/OC4wyg6X7jIkRySlX3EBeM/G+gv4Tx1Rjcrdr1b3IfTKKOyeRe5roGU+oEYveK0qCbV995anf1VJiUKhk6+TCRsbv2B2ttW7/nK2nIjAdQEhgQwDfGCz432U7i8xhNkuh5qs9TD47MbGxVSABATkjfffJpWaA135Q+WMh2G5I92jE7+MQJ0TSdXnToJNBFpjPQKRjO1m+XtptIi6crxoImr1nZoK6Nth7390ZtkBFdtS1vmpPgJNovU+mb2VuZasS4v5pKUgUr/cvF0FbOs98kWQIDAQAB'
        );*/
        $aop = new AopClient();
        $aop->gatewayUrl = static::ALI_PAY_URL;
        $aop->appId = $config['app_id'];
        $aop->rsaPrivateKey = $config['private_key'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = $config['public_key'];
        $notifyUrl = '';
        $content['body'] = $order['title'];
        $content['subject'] = $order['title'];
        $content['out_trade_no'] = $order['payment_sn'];
        $content['timeout_express'] = "30m";
        $content['total_amount'] = $order['amount'];
        $content['product_code'] = "QUICK_MSECURITY_PAY";
        $bizcontent = json_encode($content);
        $request = new AlipayTradeAppPayRequest();
        $request->setNotifyUrl($notifyUrl);
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        return htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
    }

}
