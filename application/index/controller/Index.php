<?php
namespace app\index\controller;


class Index
{
    public function index()
    {
        app('sms')->send('13714432150', '222');
        return 'hello tp5.1';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
