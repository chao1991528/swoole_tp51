<?php
namespace app\index\controller;


class Index
{
    public function sendSms()
    {
        $mobile = '13714432150';
        $code = mt_rand(1000, 9999);
        $type = 'login';
        app('swoole')->task([
            'method' => 'sendSms',
            'params' => [
                'mobile' => $mobile,
                'code' => $code,
                'type' => $type
            ]
        ]);
        return 1 ? '发送成功' : '发送失败';
    }

    public function smsCheck()
    {
        $mobile = '13714432150';
        $code = 8489;
        $type = 'login';
        $res = app('sms')->check($mobile, $code, $type);
        return $res ? '短信验证成功' : '短信验证失败';
    }
}
