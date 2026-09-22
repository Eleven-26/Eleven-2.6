<?php
/**
 * Created by PhpStorm.
 * Note: Memcache 简单封装
 * User: chenkangfu
 * Date: 2018/9/25
 * Time: 17:21
 */

namespace fuji\cache\driver;

use fuji\cache\Connection;

/**
 * Class Memcache
 * @package fuji\cache
 */
class Memcache extends Connection
{

//    /**
//     * Memcache constructor.
//     * @param array $config
//     */
//    public function __construct(array $config)
//    {
//        //调用父类构造函数
//        parent::__construct($config);
//
//    }

    //Memcache 是否压缩，true => 压缩，false => 不压缩
    const FLAG = false;
    //Memcache 连接
    private static $links = [];
    //配置
    private static $config = [];



    /**
     * Memcache constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        self::$config = $config;
    }

    /**
     * Note: Memcache 连接
     * User: Eleven
     * Date: 2018/9/26 15:00
     * @return array
     */
    public function connect1()
    {
        //链接Memcache
        if(!self::$links) {
            self::$links = new \Memcache();
            self::$links->addserver(self::$config['host'],self::$config['port']);
//            self::$links->addServer('127.0.0.1',11211);
        }
        return self::$links;
    }





}
