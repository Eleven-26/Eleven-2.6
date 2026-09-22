<?php
/**
 * Created by PhpStorm.
 * Note: Redis 简单封装
 * User: chenkangfu
 * Date: 2018/9/26
 * Time: 23:33
 */

namespace fuji\cache\driver;

use fuji\cache\Connection;

/**
 * Class Redis
 * @package fuji\cache\driver
 */
class Redis extends Connection
{

    //Redis 连接句柄
    private static $links = [
        'master' => null,
        'slave'  => []
    ];
    //是否开启主从
    private static $isMaster = false;
    //是否使用长连接
    private static $isPconnect = false;
    //连接配置
    private static $config = [];
    //一台以上的从服务器，获取一台的方式，默认随机(random)、按权重(proportion)、轮询(poll)
    private static $slaveType = 'random';

    /**
     * Redis constructor.
     * @param array $config
     * @param bool $isMaster 是否开启主从，true => 开启
     * @param bool $isPconnect 是否使用长连接Redis服务器，true => 使用
     */
    public function __construct(array $config = [], $isMaster = false, $isPconnect = false)
    {
        self::$isMaster = $isMaster;
        self::$isPconnect = $isPconnect;
        self::$config = $config;

        parent::__construct($config);
    }

    /**
     * Note: 获取缓存驱动链接，默认使用长连接
     * User: Eleven
     * Date: 2018/9/28 2:01
     * @param array $config
     * @return array|\Redis
     */
    protected function connect(array $config = [])
    {
        if (empty(self::$links['master'])) {
            //是否长连接
            if (self::$isPconnect) {
                $connect = 'pconnect';
            } else {
                $connect = 'connect';
            }

            //获取配置
            if (empty($config)) {
                $config = self::$config;
            }

            //连接主服务器
            self::$links['master'] = new \Redis();
            self::$links['master']->$connect($config['master']['host'], $config['master']['port']);

            //连接从服务器
            if (self::$isMaster && isset($config['slave'])) {
                foreach ($config['slave'] as $key => $value)
                {
                    self::$links['slave'][$key] = new \Redis();
                    self::$links['slave'][$key]->$connect($value['host'], $value['port']);
                }
            }
        }

        return self::$links;
    }

    /**
     * Note: 关闭连接
     * User: Eleven
     * Date: 2018/9/28 2:42
     * @param int $type 0 => 关闭所有，1 => 关闭主，2 => 关闭从
     * @return bool|void
     */
    public function close($type = 0)
    {
        switch ($type) {
            case 0:
                //关闭所有
                $list = $this->getRedis(true, true, true);

                $list['master']->close();

                foreach ($list['slave'] as $value) {
                    $value->close();
                }
                break;
            case 1:
                //关闭主
                $this->getRedis()->close();
                break;
            case 2:
                //关闭从
                $list = $this->getRedis(false)->close();
                foreach ($list as $value) {
                    $value->close();
                }
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * Note: 获取Redis服务
     * User: Eleven
     * Date: 2018/9/28 2:46
     * @param bool $isMaster 是否获取主服务
     * @param bool $slaveOne true => 随机获取一台从服务器，false => 返回所有从服务器
     * @param bool $isAll true => 获取所有服务器
     * @return mixed
     */
    public function getRedis($isMaster = true, $slaveOne = true, $isAll = false)
    {
        if (!$isAll) {
            if ($isMaster) {
                return self::$links['master'];
            } else {
                if ($slaveOne) {
                    //获取一台从服务器
                    return $this->getSlave();
                } else {
                    //返回所有从服务器
                    return self::$links['slave'];
                }
            }
        } else {
            return self::$links;
        }
    }

    /**
     * Note: 获取一台从服务器
     * User: Eleven
     * Date: 2018/9/28 14:55
     * @return mixed
     */
    private function getSlave()
    {
        if (!empty(self::$links['slave'])) {
            if (count(self::$links['slave']) == 1) {
                //只有一台从服务器直接返回
                return self::$links['slave'][0];
            } else {
                if (self::$slaveType == 'random') {
                    //最大的键，配置从服务器时要求从1开始递增
                    $maxKey = max(array_keys(self::$links['slave']));
                    //随机返回一台
                    return self::$links['slave'][mt_rand(1,$maxKey)];
                } elseif(self::$slaveType == 'proportion') {
                    //按按权重分配返回一台
                    $data = [];
                    $id = $this->proportion($data);
                    return self::$links['slave'][$id];

                } elseif(self::$slaveType == 'poll') {
                    //轮询返回一台

                }
            }
        }
    }

    /**
     * Note: 权重随机
     * User: Eleven
     * Date: 2018/9/28 15:11
     * @param array $array $array => ['1' => 20, '2' => 30, '3' => 50]
     * @return array
     */
    private function proportion( array $array)
    {
        //计算总概率
        $proSum = array_sum($array);
        //按值升序
        asort($array);
        //遍历概率数组
        foreach ($array as $key => $value) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $value) {
                $id = $key;
                break;
            } else {
                $proSum -= $value;
            }
        }

        return $id;
    }

    /**
     * Note: 设置Memcache
     * User: Eleven
     * Date: 2018/9/28 19:55
     * @param \fuji\cache\键 $key
     * @param \fuji\cache\值 $value
     * @param int $timeoutms 过期时间，秒，0 => 永不过期
     * @return mixed 如果成功则返回 TRUE，失败则返回 FALSE
     */
    public function set($key, $value, $timeoutms = 0)
    {
        //获取服务器连接
        $this->connect();
        //设置值
        $res = $this->getRedis()->set($key, $value, $timeoutms);

        return $res;
    }

    /**
     * Note: 获取Memcache
     * User: Eleven
     * Date: 2018/9/28 19:54
     * @param \fuji\cache\获取的键 $key
     * @return mixed 如果成功，则返回key对应的值，如果失败则返回false.
     */
    public function get($key)
    {
        //获取服务器连接
        $this->connect();
        //获取值
        $res = $this->getRedis(false)->get($key);

        return $res;
    }

    /**
     * Note: 删除Memcache
     * User: Eleven
     * Date: 2018/9/28 19:54
     * @param $key
     * @return mixed
     */
    public function del($key)
    {
        //获取服务器连接
        $this->connect();
        //设置值
        $res = $this->getRedis()->delete($key);

        return $res;
    }

    /**
     * Note: 递增
     * User: Eleven
     * Date: 2018/9/29 0:17
     * @param $key
     * @param int $incr
     * @return mixed
     */
    public function incr($key, $incr = 1)
    {
        //获取服务器连接
        $this->connect();
        //设置值
        $res = $this->getRedis()->incr($key, $incr);

        return $res;
    }

    /**
     * Note: 递减
     * User: Eleven
     * Date: 2018/9/29 0:18
     * @param $key
     * @param int $decr
     * @return mixed
     */
    public function decr($key, $decr = 1)
    {
        //获取服务器连接
        $this->connect();
        //设置值
        $res = $this->getRedis()->decr($key, $decr);

        return $res;
    }



}
