<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Time: 2019/12/13 0013  22:54
 */

namespace app\common\lib;


class RedisObj
{
    private $redis;

    //当前数据库ID号
    protected $dbId = 0;

    //当前权限认证码
    protected $auth;

    /**
     * 实例化的对象,单例模式.
     * @var /Redis
     */
    static private $_instance = [];

    private $k;

    //连接属性数组
    protected $attr = [
        //连接超时时间，redis配置文件中默认为300秒
        'timeout' => 30,
        //选择的数据库。
        'db_id' => 0,
    ];

    //什么时候重新建立连接
    protected $expireTime;

    protected $host;

    protected $port;


    private function __construct($config, $attr = [])
    {
        $this->attr = array_merge($this->attr, $attr);
        $this->redis = new \Redis();
        $this->port = $config['port'] ? $config['port'] : 6379;
        $this->host = $config['host'];
        $this->redis->connect($this->host, $this->port, $this->attr['timeout']);

        if ($config['auth']) {
            $this->redis->auth($config['auth']);
            $this->auth = $config['auth'];
        }

        $this->expireTime = time() + $this->attr['timeout'];
    }

    /**
     * 得到实例化的对象.
     * 为每个数据库建立一个连接
     * 如果连接超时，将会重新建立一个连接
     * @param array $config
     * @param $attr
     * @return Redis
     */
    public static function getInstance($redisName)
    {
        $configs = config('redis.');
        if(!empty($configs[$redisName])){
            $attr['db_id'] = $configs[$redisName]['db'];
            $config['host'] = $configs[$redisName]['host'];
            $config['port'] = $configs[$redisName]['port'];
            $config['auth'] = $configs[$redisName]['password'];
            unset($configs);
        } else {
            throw new \Exception('redis配置不存在');
        }
        //如果是一个字符串，将其认为是数据库的ID号。以简化写法。
        if (!is_array($attr)) {
            $dbId = $attr;
            $attr = [];
            $attr['db_id'] = $dbId;
        }

        $attr['db_id'] = $attr['db_id'] ? $attr['db_id'] : 0;


        $k = md5(implode('', $config) . $attr['db_id']);
        if (empty(static::$_instance[$k]) || !(static::$_instance[$k] instanceof self)) {

            static::$_instance[$k] = new self($config, $attr);
            static::$_instance[$k]->k = $k;
            static::$_instance[$k]->dbId = $attr['db_id'];

            //如果不是0号库，选择一下数据库。
            if ($attr['db_id'] != 0) {
                static::$_instance[$k]->getRedis()->select($attr['db_id']);
            }
        } elseif (time() > static::$_instance[$k]->expireTime) {
            static::$_instance[$k]->getRedis()->close();
            static::$_instance[$k] = new self($config, $attr);
            static::$_instance[$k]->k = $k;
            static::$_instance[$k]->dbId = $attr['db_id'];

            //如果不是0号库，选择一下数据库。
            if ($attr['db_id'] != 0) {
                static::$_instance[$k]->getRedis()->select($attr['db_id']);
            }
        }
        return static::$_instance[$k]->getRedis();
    }

    private function __clone()
    {
    }

    /**
     * 执行原生的redis操作
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }
}