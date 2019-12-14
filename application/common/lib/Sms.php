<?php


namespace app\Common\lib;

use app\index\lib\RedisKey;

class Sms
{
    public function send($mobile, $code, $type)
    {
        $redis = RedisObj::getInstance(Constant::REDIS_ONE);
        $smsConfig = config('sms.');
        if(empty($smsConfig[$type])){
            throw new \Exception('短信配置信息不存在');
        }
        echo 222;
        //todo 根据短信配置设置短信模板
        $redis->set(sprintf(RedisKey::MOBILE_SMS_CODE, $mobile, $type), $code, $smsConfig[$type]['timeout']);
        return true;
    }

    public function check($mobile, $code, $type)
    {
        $redis = RedisObj::getInstance(Constant::REDIS_ONE);
        $key = sprintf(RedisKey::MOBILE_SMS_CODE, $mobile, $type);
        if($code == $redis->get($key)){
            return $redis->delete($key);
        }
        return false;
    }
}