<?php
/**
 * Created by PhpStorm.
 * Note: 数据库配置
 * User: chenkangfu
 * Date: 2018/9/16
 * Time: 23:58
 */

return [

    //数据库配置，支持一主多从
    'db' => array(
        //数据库调试模式
        'debug' => true,
        //数据库驱动
        'driver' => 'mysql',
        //主库
        'master' => array(
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'eleven',
            'username' => 'root',
            'password' => 'root',
        ),
        //从库
        'slave' => array(
            0 => array(
                'host' => '127.0.0.1',
                'database' => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
            ),
            1 => array(
                'host' => '127.0.0.1',
                'database' => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
            ),
        ),
    )
];