<?php
/**
 * 配置文件
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 23:21
 */

return [

    //环境:测试环境 => development,线上环境 => production
    'environment' => 'development',

    //Memcache 配置
    'memcache' => [
        'host' => '127.0.0.1',
        'port' => '11211'
    ],

    //Redis 配置，支持一主多从配置
    'redis' => [
        //主服务器
        'master' => [
            'host' => '127.0.0.1',
            'port' => '6379'
        ],
        //多台从服务器下标从1开始递增
        'slave' => [
            1 => [
                'host' => '127.0.0.1',
                'port' => '6379',
                'weight' => 30
            ],
            2 => [
                'host' => '127.0.0.1',
                'port' => '6379',
                'weight' => 70
            ],
        ],
    ],

    //RabbitMQ配置
    'rabbitmq' => [
        'host' => '127.0.0.1',
        'port' => 5672,
        'vhost' => '/',
        'login' => 'guest',
        'password' => 'guest',
    ],

    //短信配置
    'sendMessage' => [

    ],

    //邮件配置
    'sendEmails' => [

    ],

];