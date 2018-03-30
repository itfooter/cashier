<?php
/**
 * Created by PhpStorm.
 * @author:beller
 */

namespace Lib;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Http {

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function __call($name, $arguments)
    {
        //添加代理
        if (config('weixin.proxy')) {
            if (isset($arguments[1])) {
                $arguments[1] += ['proxy'=> config('weixin.proxy')];
            } else {
                $arguments[1] = ['proxy'=> config('weixin.proxy')];
            }
        }
        $response = $this->client->$name($arguments[0], $arguments[1])->getBody();
        return $response;

        /*$response = json_decode($this->client->$name($arguments[0], $arguments[1])->getBody()->getContents(), true);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new \Exception(isset($response['errmsg']) ? "errcode:".$response['errcode']."->".$response['errmsg'] : 'Unknown');
        }
        return $response;*/
    }

}