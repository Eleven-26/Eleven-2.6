<?php
/**
 * Created by PhpStorm.
 * Note: 缓存抽象基类
 * User: chenkangfu
 * Date: 2018/9/26
 * Time: 15:23
 */

namespace fuji\cache;


/**
 * Class Connection
 * @package fuji\cache
 */
abstract class Connection
{

    //Memcache 连接
    private static $links = [];
    //配置
    private static $config = [];
    //Memcache 是否压缩，true => 压缩，false => 不压缩
    const FLAG = false;


    /**
     * Memcache constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        self::$config = $config;
    }

    /**
     * Note: 获取缓存驱动链接
     * User: Eleven
     * Date: 2018/9/26 15:28
     * @return mixed
     */
    protected function connect()
    {
        //链接Memcache
        if(!self::$links) {
            self::$links = new \Memcache();
            self::$links->addserver(self::$config['host'],self::$config['port']);
//            self::$links->addServer('127.0.0.1',11211);
        }
        return self::$links;
    }

    /**
     * Note: 设置Memcache
     * User: Eleven
     * Date: 2018/9/26 15:09
     * @param $key 键
     * @param  $value 值
     * @param int $timeoutms 过期时间，秒，0 => 永不过期
     * @return mixed 如果成功则返回 TRUE，失败则返回 FALSE
     */
    public function set($key, $value, $timeoutms = 0)
    {
        //设置值
        $res = $this->connect()->set($key, $value,self::FLAG, $timeoutms);

        return $res;
    }

    /**
     * Note: 获取Memcache
     * User: Eleven
     * Date: 2018/9/26 23:09
     * @param $key  获取的键
     * @return mixed 如果成功，则返回key对应的值，如果失败则返回false.
     */
    public function get($key)
    {
        //获取值
        $res = $this->connect()->get($key);

        return $res;
    }

    /**
     * Note: 删除Memcache
     * User: Eleven
     * Date: 2018/9/26 23:09
     * @param $key
     * @return mixed
     */
    public function del($key)
    {
        //获取值
        $res = $this->connect()->delete($key);

        return $res;
    }

    /**
     * Note: 关闭连接
     * User: Eleven
     * Date: 2018/9/26 23:12
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

}
