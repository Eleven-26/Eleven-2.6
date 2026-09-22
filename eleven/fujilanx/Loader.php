<?php
/**
 * Created by PhpStorm.
 * Note: 自动加载
 * User: chenkangfu
 * Date: 2018/8/27
 * Time: 23:46
 */

namespace fuji;

/**
 * Class Loader
 * @package fuji
 */
class Loader{

    /**
     * @var array PSR-4 的加载目录
     */
    private static $prefixDirsPsr4 = [];

    /**
     * @var array PSR-0 加载失败的回退目录
     */
    private static $fallbackDirsPsr0 = [];

    /**
     * @var array 类名映射
     */
    protected static $classMap = [];

    /**
     * Note: 注册自动加载
     * User: Eleven
     * Date: 2018/09/11 22:30
     */
    public static function register()
    {
        //注册自动加载方法
        spl_autoload_register('fuji\\Loader::autoload',true,true);

        //注册命名空间目录映射
        self::addNamespace([
            'App'   => APP_PATH,
            'fuji'  => FUJI_PATH,
            'ven'   => VENDOR_PATH,
            'lib'   => LIB_PATH,
            'mod'   => MODEL_PATH
        ]);
    }

    /**
     * Note: 自动加载方法
     * User: Eleven
     * Date: 2018/09/11 23:00
     * @param $class
     * @return mixed
     */
    public static function autoload($class)
    {
        //解析文件路径
        $absPath = self::findFile($class);
        if(file_exists($absPath))
        {
            if(isset(self::$classMap[$class])) {
                //不重复引入
                return self::$classMap[$class];
            } else {
                //引入文件
                self::includeFile($absPath);

                //保存引入过的文件，下次不再引入
                self::$classMap[$class] = $class;
            }
        }
    }

    /**
     * Note: 解析文件路径
     * User: Eleven
     * Date: 2018/09/12 23:38
     * @param $class
     * @return string
     */
    private static function findFile($class)
    {
        // 命名空间前缀
        $vendor = substr($class, 0, strpos($class, '\\'));

        //命名空间路径映射
        $vendorDirArr = static::$prefixDirsPsr4[$vendor.DS];

        //文件相对路径
        $filePath = substr($class, strlen($vendor)) . '.php';

        //文件绝对路径
        $absPath = strtr($vendorDirArr[0] . $filePath, '\\', DIRECTORY_SEPARATOR);

        return $absPath;
    }

    /**
     * Note: 导入文件
     * User: Eleven
     * Date: 2018/09/12 23:41
     * @param $file
     * @return mixed
     */
    private static function includeFile($file)
    {
        if(is_file($file))
        {
            return include $file;
        }
    }

    /**
     * Note: 注册命名空间目录映射
     * User: Eleven
     * Date: 2018/09/11 23:12
     * @param $namespace
     * @param string $path
     */
    public static function addNamespace($namespace,$path = '')
    {
        if(is_array($namespace)){//数组循环添加
            foreach($namespace as $prefix => $paths){
                self::addPsr4($prefix.'\\',rtrim($paths,DS));//$paths 去掉右边的反斜杠 "/"
            }
        }else{
            self::addPsr4($namespace.'\\',rtrim($path,DS));
        }
    }

    /**
     * Note: 添加命令空间 Psr4
     * User: Eleven
     * Date: 2018/09/11 23:16
     * @param $prefix array|string $prefix  空间前缀
     * @param $paths array 路径
     */
    private static function addPsr4($prefix,$paths)
    {

        if(!$prefix){
            self::$fallbackDirsPsr0 = array_merge(self::$fallbackDirsPsr0,(array)$paths);
        }elseif(!isset(self::$prefixDirsPsr4[$prefix])){
            // Register directories for a new namespace.
            $length = strlen($prefix);
            if ('\\' !== $prefix[$length - 1]) {
                throw new \InvalidArgumentException(
                    "A non-empty PSR-4 prefix must end with a namespace separator."
                );
            }

//            self::$prefixLengthsPsr4[$prefix[0]][$prefix] = $length;
            self::$prefixDirsPsr4[$prefix]                = (array) $paths;

        }else{
            //添加命名空间映射
            array_merge(self::$prefixDirsPsr4[$prefix], (array) $paths);
        }
    }

}