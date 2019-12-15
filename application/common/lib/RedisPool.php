<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Time: 2019/12/13 0013  22:54
 */

namespace app\common\lib;

/**
 * Class RedisPool
 * @package app\common\lib
 * 异步php redis类(未经过实践，不知能否正常使用)
 */
class RedisPool
{
    private static $instance;
    private $pool;
    private $config;

    public static function getInstance($config = null)
    {
        if (empty(self::$instance)) {
            if (empty($config)) {
                throw new \Exception("redis config empty");
            }
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    public function __construct($config)
    {
        if (empty($this->pool)) {
            $this->config = $config;
            $this->pool = new \chan($config['pool_size']);
            for ($i = 0; $i < $config['pool_size']; $i++) {
                go(function() use ($config) {
                    $redis = new \Swoole\Coroutine\Redis();
                    $res = $redis->connect($config['host'], $config['port']);
                    if(!empty($config['password'])){
                        $redis->auth($config['password']);
                    }
                    if($config['db'] !== 0){
                        $redis->select($config['db']);
                    }
                    if ($res === false) {
                        throw new \Exception("Failed to connect redis");
                    } else {
                        $this->pool->push($redis);
                    }
                });
            }
        }
    }

    public function get()
    {
        if ($this->pool->length() > 0) {
            $redis = $this->pool->pop($this->config['pool_get_timeout']);
            if (false === $redis) {
                throw new \Exception("Pop redis timeout");
            }
            defer(function () use ($redis) { //释放
                $this->pool->push($redis);
            });
            return $redis;
        } else {
            throw new \Exception("Pool is empty");
        }
    }
}