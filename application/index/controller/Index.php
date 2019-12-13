<?php
namespace app\index\controller;


class Index
{
    public function index()
    {
        return 'hello tp5.1';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
