<?php
/**
 * Created by PhpStorm.
 * Note: 日志配置文件
 * User: chenkangfu
 * Date: 2018/9/23
 * Time: 15:57
 */

return [
    'log' => [
//        'driver' => 'file',//驱动
        'driver' => 'Seaslog',//驱动
        'debug' => true,//是否开启日志
//        'filePath' => LOG_PATH . 'file/'//File文件日志存放目录
        'filePath' => LOG_PATH . 'seaslog/'//SeasLog文件日志存放目录

    ],
];