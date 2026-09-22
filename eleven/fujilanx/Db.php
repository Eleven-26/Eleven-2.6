<?php
/**
 * 数据库操作入口
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 22:28
 */

namespace fuji;

/**
 * Class Db
 * @package fuji
 * @method Query table(string $table) static 指定数据表（含前缀）
 * @method mixed query(string $sql, array $bind = [], boolean $master = false, bool $pdo = false) static SQL查询
 */
class Db
{

    /**
     * @var array 数据库连接实例
     */
    private static $instance = [];

    /**
     * Note: 数据库初始化，并取得数据库类实例
     * User: Eleven
     * Date: 2018/9/17 1:27
     * @param array $config 数据库配置信息
     * @param bool $name 连接标识 true 强制重新连接
     * @return mixed
     */
    public static function connect($config = [], $name = false)
    {
        if($name === false)
        {
            $name = md5(serialize($config));
        }

        if($name === true || !isset(self::$instance[$name]))
        {
            //配置解析
            if(empty($config)){
                $config = Config::getConfig('db');
                $config = array_merge($config,$config['master']);//测试一个库
            }

            if($name === true)
            {
                $name = md5(serialize($config));
            }

            //数据库引擎，把首字母转成大写
            $class = '\\fuji\\database\\connector\\' . ucwords($config['driver']);

            self::$instance[$name] =  new $class($config);
        }

        return self::$instance[$name];
    }

    /**
     * Note: 清除连接实例
     * User: Eleven
     * Date: 2018/9/18 2:22
     */
    public static function clear()
    {
        self::$instance = [];
    }

    /**
     * Note: 代理调用查询Query类的方法
     * User: Eleven
     * Date: 2018/9/17 1:29
     * @param $method
     * @param $params
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([self::connect(), $method], $params);
    }

}