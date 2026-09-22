<?php
/**
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/13
 * Time: 0:00
 */

namespace fuji;

/**
 * Class App
 * @package fuji
 */
class App
{
    /**
     * Note: 初始化应用
     * User: Eleven
     * Date: 2018/9/15 22:41
     */
    public static function run()
    {
        //初始化配置
        self::initCommon();

        /**
         * URL解析
         * $urls => Array
            (
                [module] => admin
                [controller] => index
                [action] => index
                [id] => 1126
            )
         */
        $urls = Url::getRoute();

        //路由分发调用
        self::exec($urls);
    }

    /**
     * Note: 初始化配置
     * User: Eleven
     * Date: 2018/10/30 16:16
     */
    private static function initCommon()
    {
        //注册配置信息

        //编码，UTF-8
        header("Content-type:text/html;charset=utf-8");
        //时区，中华人名共和国
        ini_set('date.timezone','PRC');

        //调试、开发模式切换
        if (defined('ENVIRONMENT'))
        {
            switch (ENVIRONMENT)
            {
                //测试
                case 'development':
                    ini_set('display_errors', true);
                    error_reporting(E_ALL);
                    break;
                //正式
                case 'production':
                    ini_set('display_errors', FALSE);
                    error_reporting(0);
                    break;

                default:
                    exit('The application environment is not set correctly.');
            }
        }
    }

    /**
     * Note: 执行调用分发
     * User: Eleven
     * Date: 2018/9/16 17:25
     * @param $urls
     * @throws \ReflectionException
     */
    private static function exec($urls)
    {
        //1 直接实例化类并调用方法
//        $class = new \App\admin\controller\Index();
//        $res = $class->$urls['action']();

        //2 通过回调函数
//        $class = new \App\admin\controller\Index();
//        $res = call_user_func_array(array($class,$urls['action']),array());

        //3 通过反射类调用
        if ($urls['module'] == 'api') {
            //api类路径
            $path = DS . 'App' . DS . $urls['module'] . DS . 'mobile' . DS . $urls['controller'];
        } else {
            //admin、home类路径
            $path = DS . 'App' . DS . $urls['module'] . DS . 'controller' . DS . $urls['controller'];
        }

//        echo $path;exit;

        //建立类的反射类
        $class = new \ReflectionClass($path);

        //实例化类
        $instance  = $class->newInstance();

        //获取类中的执行的方法
        $action = $class->getmethod($urls['action']);

        //执行操作
        $action->invoke($instance);
    }

}