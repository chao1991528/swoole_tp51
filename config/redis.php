<?php
use \think\facade\Env;

return [
    'index_redis' => [
        'host'          => Env::get('redis.host','127.0.0.1'),
        'port'          => Env::get('redis.port',6379),
        'password'      => Env::get('redis.password','123456'),
        'timeout'       => Env::get('redis.timeout',1800),
        'db'            => Env::get('redis.db',0)
    ]
];