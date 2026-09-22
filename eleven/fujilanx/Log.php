<?php
/**
 * Created by PhpStorm.
 * Note: 日志系统
 * User: chenkangfu
 * Date: 2018/9/23
 * Time: 15:50
 */

namespace fuji;


/**
 * Class Log
 * @package fuji
 */
class Log
{

    //日志连接实例
    private static $instance;
    //日志模块
    const MODULE = 'system';

    //日志级别
    const DEBUG     = 'debug';      //仅仅是在debug模式下才可以记录的debug日志
    const INFO      = 'info';       //重要事件，例如：用户登录和SQL记录。
    const NOTICE    = 'notice';     //一般性重要的事件
    const WARNING   = 'warning';    //出现非错误性的异常。例如：使用了被弃用的API、错误地使用了API或者非预想的不必要错误
    const ERROR     = 'error';      //运行时出现的错误，不需要立刻采取行动，但必须记录下来以备检测
    const CRITICAL  = 'critical';   //紧急情况， 例如：程序组件不可用或者出现非预期的异常。
    const ALERT     = 'alert';      //必须立刻采取行动的错误级别，比如在整个网站都垮掉了、数据库不可用了或者其他的情况下，应该发送一条警报短信把你叫醒。
    const EMERGENCY = 'emergency';  //紧急情况，最高级别，系统已不可用

    /**
     * Note: 根据配置获得日志驱动类链接
     * User: Eleven
     * Date: 2018/9/23 16:15
     * @return mixed
     */
    private static function init()
    {
        if(!self::$instance) {
            //获取日志配置文件
            $config = Config::getConfig('log');
            //实例化日志驱动
            $class = '\\fuji\\log\\driver\\' . ucwords($config['driver']);
            self::$instance = new $class($config);
        }

        return self::$instance;
    }

    /**
     * Note: 写入日志
     * User: Eleven
     * Date: 2018/9/23 16:27
     * @param string $message 信息
     * @param string $type 类型
     * @param string $module 模块
     * @param array|string $content 变量赋值
     * @return
     * @internal param 名称 $name
     */
    public static function writeLog($message, $type = self::DEBUG, $module = self::MODULE, $content = array())
    {
        //获取日志驱动实例
        if(!self::$instance){
            self::$instance = self::init();
        }

        //写入日志
        $res = self::$instance->save($message,$type,$module,$content);

        return $res;
    }

    /**
     * Note: 读取日志
     * User: Eleven
     * Date: 2018/9/24 0:28
     * @param null|string $type 类型，为空则获取所有
     * @return string
     */
    public static function getLog($type = 'all')
    {
        //获取日志驱动实例
        if(!self::$instance){
            self::$instance = self::init();
        }

        //写入日志
        $log = self::$instance->get($type);

        return $log;
    }

    /**
     * Note: 获取SeasLog的日志存放目录
     * User: Eleven
     * Date: 2018/9/24 16:59
     * @return mixed
     */
    public static function getBasePath()
    {
        //获取日志驱动实例
        if(!self::$instance){
            self::$instance = self::init();
        }

        $res = self::$instance->getBasePath();

        return $res;
    }

}