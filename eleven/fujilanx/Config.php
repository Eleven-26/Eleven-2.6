<?php
/**
 * Created by PhpStorm.
 * Note: 获取配置
 * User: chenkangfu
 * Date: 2018/9/16
 * Time: 22:36
 */

namespace fuji;

/**
 * Class Config
 * @package fuji
 */
class Config implements \ArrayAccess
{
    /**
     * @var array   保存配置参数
     */
    private static $config = [];

    /**
     * @var 类的实例对象
     */
    private static $instance;

    /**
     * @var 所有配置文件
     */
    private static $fileNames;

    /**
     * @var 配置文件目录
     */
    private static $path;

    /**
     * Config constructor.
     */
    private function __construct()
    {
        self::$path = CONF_PATH;
        self::loadFile(CONF_PATH);
    }

    /**
     * Note: 单例，获取当前类对象
     * User: Eleven
     * Date: 2018/9/16 23:17
     * @return Config|类的实例对象
     */
    public static function getInstance()
    {
        //判断$instance是否是当前类的对象
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    ////////////////////////////////////////////////////START 以下是通过实现PHP预定义接口:ArrayAccess 获取配置信息 ////////////////////////////////////////////////////////////////////////////////////

    /**
     * 1、调用方法
     * $config = Config::getInstance();
     * Eleven($config['database']);
     */

    /**
     * Note: 检查数据是否存在
     * User: Eleven
     * Date: 2018/9/16 23:20
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset(self::$config[$offset]);
    }

    /**
     * Note: 获取数据
     * User: Eleven
     * Date: 2018/9/16 23:43
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        //存在则返回
        if(isset(self::$config[$offset])){
            return self::$config[$offset];
        }

        //读取所有配置
        foreach(self::$fileNames as $file){
            self::$config = array_merge(self::$config,include self::$path . $file);
        }

        return self::$config[$offset];
    }

    /**
     * Note: 设置数据
     * User: Eleven
     * Date: 2018/9/16 23:44
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset,$value)
    {
        throw new \Exception('不需要设置配置');
    }

    /**
     * Note: 删除数据
     * User: Eleven
     * Date: shijian
     * @param mixed $offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception('不需要删除配置');
    }

    /////////////////////////////////////////////////END 以上是通过实现PHP预定义接口:ArrayAccess 获取配置信息 ////////////////////////////////////////////////////////////////////////////////////

    /**
     * 2、调用方法
     * $config = Config::getConfig();
     */

    /**
     * Note: 获取配置
     * User: Eleven
     * Date: 2018/9/16 22:48
     * @param string $name
     * @return array
     */
    public static function getConfig($name = null)
    {
//        self::getInstance();

        //配置文件目录
        self::$path = CONF_PATH;

        //加载配置文件
        self::loadFile(CONF_PATH);

        //存在则返回
        if(isset(self::$config[$name])){
            return self::$config[$name];
        }

        //读取所有配置
        foreach(self::$fileNames as $file){
            self::$config = array_merge(self::$config,include self::$path . $file);
        }

        //没有传参获取所有配置
        if($name == null)
        {
            return self::$config;
        }

        return self::$config[$name];
    }

    /**
     * Note: 解析配置文件
     * User: Eleven
     * Date: 2018/9/16 22:50
     * @param $config
     * @param string $type
     * @param string $name
     */
    private static function parse($config, $type = '', $name = '')
    {

    }

    /**
     * Note: 获取所有配置文件
     * User: Eleven
     * Date: 2018/9/17 0:04
     * @param $path
     * @return array
     */
    private static function loadFile($path) {
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path . '/' . $file)) {
                    self::loadFile($path . '/' . $file);
                } else {
                    $result[] = basename($file);
                }
            }
        }
        self::$fileNames = $result;
    }

}