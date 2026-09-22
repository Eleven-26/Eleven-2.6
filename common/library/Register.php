<?php
/**
 * 注册模式，解决全局共享和交换对象
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-22
 * Time: 10:54
 */

namespace lib;


/**
 * Class Register
 * @package lib
 */
class Register
{
    /**
     * @var 全局对象
     */
    private static $objects;

    /**
     * Note：将对象注册到全局的树上
     * User：Eleven
     * Date：2019-1-22 10:58
     * @param $key
     * @param $objects
     */
    public static function setObjects($key, $objects)
    {
        self::$objects[$key] = $objects;
    }

    /**
     * Note：获取某个注册到树上的对象
     * User：Eleven
     * Date：2019-1-22 11:00
     * @param $key
     * @return mixed
     */
    public static function getObjects($key)
    {
        return self::$objects[$key];
    }

    /**
     * Note：移除某个注册到树上的对象
     * User：Eleven
     * Date：2019-1-22 11:01
     * @param $key
     */
    public static function unsetObjects($key)
    {
        unset(self::$objects[$key]);
    }

}