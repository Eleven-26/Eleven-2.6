<?php
/**
 * Created by PhpStorm.
 * Note: SeasLog 日志驱动
 * User: chenkangfu
 * Date: 2018/9/24
 * Time: 3:04
 */

namespace fuji\log\driver;

use Seaslog as SeLog;


/**
 * Class Seaslog
 * @package fuji\log\driver
 */
class Seaslog
{

    //日志目录
    private static $filePath;

    /**
     * Seaslog constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        self::$filePath = $config['filePath'];
        self::setBasePath(self::$filePath);
    }

    /**
     * Note: 设置日志存放目录
     * User: Eleven
     * Date: 2018/9/24 3:11
     * @param $path
     * @return mixed
     */
    public function setBasePath($path)
    {
        //保存目录
        self::$filePath = $path;

        //设置目录
        $res = SeLog::setBasePath($path);

        return $res;
    }

    /**
     * Note: 获取日志存放目录
     * User: Eleven
     * Date: 2018/9/24 3:12
     * @return mixed
     */
    public function getBasePath()
    {
        return SeLog::getBasePath();
    }

    /**
     * Note: 写入日志
     * User: Eleven
     * Date: 2018/9/24 3:26
     * @param $message  日志信息
     * @param $type 日志级别
     * @param string $module    日志模块
     * @param array $content    变量赋值  SeasLog::debug('this is a {userName} debug',array('{userName}' => 'Eleven'));
     * @return
     */
    public function save($message, $type, $module, $content = array())
    {
        $res = SeLog::log($type, $message, $content, $module);

        return $res;
    }

    /**
     * Note: 读取日志
     * User: Eleven
     * Date: 2018/9/24 3:27
     * @param $type
     */
    public function get($type)
    {
//        $data = SeLog::analyzerDetail($type,date('Ymd',time()));
//        $data = SeLog::analyzerCount();
        $data = SeLog::analyzerDetail($type);

        return $data;
    }

}
