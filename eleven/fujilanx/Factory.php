<?php
/**
 * 工厂类
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/8/28
 * Time: 21:54
 */

namespace fuji;

class Factory
{
    /**
     * 创建数据库对象
     * @param $db
     * @return Mysql|null
     */
    public function createDb($db)
    {
        $mysql_config = [
            'host' => '',
        ];
        switch($db)
        {
            case "mysql":
                $object = Mysql::getInstance($mysql_config);
                break;
            case 'mango';
                break;
            default:
                $object = null;
        }

        return $object;
    }

    /**
     * 创建缓存对象
     * @param $cache
     * @return mixed
     */
    public function createCache($cache)
    {

        $cache;
        $object = null;
        return $object;
    }

    /**
     * 创建队列对象
     * @param $queue
     * @return mixed
     */
    public function createQueue($queue)
    {

        $queue;
        $object = null;
        return $object;
    }
}