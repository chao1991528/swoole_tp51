<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Env;
use app\common\lib\Task;
use think\facade\Log;

// +----------------------------------------------------------------------
// | Swoole设置 php think swoole命令行下有效
// +----------------------------------------------------------------------
return [
    // 扩展自身配置
    'host'                  => '0.0.0.0', // 监听地址
    'port'                  => 9501, // 监听端口
    'daemonize'             =>  true,
    'mode'                  => '', // 运行模式 默认为SWOOLE_PROCESS
    'sock_type'             => '', // sock type 默认为SWOOLE_SOCK_TCP
    'server_type'           => 'http', // 服务类型 支持 http websocket
    'app_path'              => '/mnt/hgfs/share/tp51/application/', // 应用地址 如果开启了 'daemonize'=>true 必须设置（使用绝对路径）
    'file_monitor'          => false, // 是否开启PHP文件更改监控（调试模式下自动开启）
    'file_monitor_interval' => 2, // 文件变化监控检测时间间隔（秒）
    'file_monitor_path'     => [], // 文件监控目录 默认监控application和config目录

    // 可以支持swoole的所有配置参数
    'pid_file'              => Env::get('runtime_path') . 'swoole.pid',
    'log_file'              => Env::get('runtime_path') . 'swoole.log',
    'document_root'         => Env::get('root_path') . 'public',
    'enable_static_handler' => true,
    'timer'                 => true,//是否开启系统定时器
    'interval'              => 500,//系统定时器 时间间隔
    'task_worker_num'       => 1,//swoole 任务工作进程数量

    /**
     * 自定义投递任务
     * @param swoole_server $serv
     * @param int $taskId
     * @param int $srcWorkerId
     * @param mixed $data
     */
    'Task' => function($serv, $taskId, $srcWorkerId, $data){
        $taskObj = new Task();
        $classMethods = get_class_methods(Task::class);
        Log::write(json_encode($data) . '****************************');
        if (!in_array($data['method'], $classMethods)) {
            return 'method:'.$data['method'].' not find in'.Task::class;
        }
        return call_user_func_array([$taskObj, $data['method']], $data['params']);
    },
];
