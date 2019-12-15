<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2019/12/15
 * Time: 18:09
 */


namespace app\common\lib;


use app\index\lib\RedisKey;
use think\facade\Log;

class Task
{
    public function sendSms($mobile, $code, $type)
    {
        $res = app('sms')->send($mobile, $code, $type);
        if($res !== true){
            Log::write('发送短信失败',Log::ERROR);
        } else {
            $redis = RedisObj::getInstance(Constant::REDIS_ONE);
            $smsConfig = config('sms.');
            $redis->set(sprintf(RedisKey::MOBILE_SMS_CODE, $mobile, $type), $code, $smsConfig[$type]['timeout']);
        }

        return true;
    }
}