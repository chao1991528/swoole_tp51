<?php
use \think\facade\Env;

return [
    'host'          => Env::get('redis.host','127.0.0.1'),
    'port'          => Env::get('redis.port',6379),
    'password'      => Env::get('redis.password','123456'),
    'db'            => Env::get('redis.db',0)
];