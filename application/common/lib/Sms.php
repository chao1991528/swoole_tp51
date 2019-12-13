<?php


namespace app\Common\lib;


class Sms
{
    public function send($mobile, $code)
    {
        $reidsConfig = config('redis.');
//        $reidsConfig = 11;
//        var_dump($reidsConfig);return;
        $redis = RedisObj::getInstance($reidsConfig, $reidsConfig['db']);
         return ($redis);
        return true;
    }
}