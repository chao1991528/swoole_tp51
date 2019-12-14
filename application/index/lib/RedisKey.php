<?php


namespace app\index\lib;


class RedisKey
{
    const MOBILE_SMS_CODE = 'sms_%s_%s';//第一个%s是手机号，第二个是类型（登录/注册/找回密码...)
}