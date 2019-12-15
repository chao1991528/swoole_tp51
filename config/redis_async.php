<?php
use \think\facade\Env;

return [
    'index_redis_async' => [
        'host'                  => Env::get('redis.host','127.0.0.1'),
        'port'                  => Env::get('redis.port',6379),
        'password'              => Env::get('redis.password','123456'),
        'pool_get_timeout'      => Env::get('redis.pool_get_timeout',1),
        'db'                    => Env::get('redis.db',0),
        'pool_size'             => Env::get('redis.pool_size',4)
    ]
];