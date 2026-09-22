<?php
/**
 * Created by PhpStorm.
 * Note: URL解析
 * User: chenkangfu
 * Date: 2018/9/16
 * Time: 0:58
 */

namespace fuji;

/**
 * Class Url
 * @package fuji
 */
class Url
{

    //定义正则表达式常量
    const REGEX_ANY = "([^/]+?)";                   //非/开头任意个任意字符
    const REGEX_INT = "([0-9]+?)";                  //数字
    const REGEX_ALPHA = "([a-zA-Z_-]+?)";           //字母_-
    const REGEX_ALPHANUMERIC = "([0-9a-zA-Z_-]+?)"; //任意个字母数字_-
    const REGEX_STATIC = "%s";                      //占位符
    const ANY = "(/[^/]+?)*";                       //任意个非/开头字符

    private static $routes = array();             //保存路径正则表达式
    private static $parts = array();              //保存URL参数

    /**
     * Note: 添加路由正则表达式
     * User: Eleven
     * Date: 2018/9/16 16:07
     * @internal param $route
     */
    public static function addRoute()
    {
        $routes = [
            '/alpha:module/alpha:controller/[0-9]+?:a1a',
            '/alpha:module/alpha:controller/alpha:action',
            '/alpha:module/alpha:controller/alpha:action/(www[0-9]+?):id'
        ];

        foreach($routes as $route) {
            self::$routes[] = self::parseRoute($route);
        }
    }

    /**
     * Note: 路由正则过滤
     * User: Eleven
     * Date: 2018/9/16 16:07
     * @param $route
     * @return string
     */
    private static function parseRoute($route)
    {
        //以"/"分解路由规则
        $parts = explode('/',$route);

        //去除第一个空元素
        if(!$parts[0]){
            unset($parts[0]);
        }

        //拼接正则规则
        $regex="@^";
        foreach($parts as $part)
        {
            $regex.="/";
            $args = explode(':',$part);
            if(count($args) !== 2)
            {
                continue;
            }

            //删除并返回该元素
            $type = array_shift($args);
            $key = array_shift($args);

            //使参数标准化，排除其他非法符号
            $key = self::normalize($key);

            //为了后面preg_match正则匹配做铺垫
            $regex .= '(?P<'. $key .'>';
            switch (strtolower($type))
            {
                case 'int'://数字
                    $regex .= self::REGEX_INT;
                    break;
                case 'alpha'://字母
                    $regex .= self::REGEX_ALPHA;
                    break;
                case "alphanum"://字母数字
                    $regex .= self::REGEX_ALPHANUMBERIC;
                    break;
                default:
                    $regex.=$type; #自定义正则表达式
                    break;
            }
            $regex.=")";
        }

        //其他URL参数
        $regex .= self::ANY;
        $regex .= '$@u';

        return $regex;
    }

    /**
     * Note: 使参数标准化，不能存在符号，只能是a-zA-Z0-9组合
     * User: Eleven
     * Date: 2018/9/16 16:27
     * @param $param
     * @return mixed
     */
    private static function normalize($param)
    {
        $param = preg_replace("/[^a-zA-Z0-9]/", '', $param);
        return $param;
    }


    /**
     * Note: URL解析模块、控制器、操作
     * User: Eleven
     * Date: 2018/9/15 22:41
     * @return array|bool 失败返回 false
     * @internal param $request
     */
    public static function getRoute()
    {
        //http://www.eleven.com/admin/index/index/id/1126
        $request = $_SERVER['REQUEST_URI'];

        //除去右边多余的斜杠/
        $request = rtrim($request,'/');

        //以"/"拆分
        $arguments = explode('/',$request);

        //去除空元素
        $arguments = array_filter($arguments);

        //个数
        $num = count($arguments);

        //判断个数，不足则补全，模块、控制器、操作，即构建MVC结构URL
        switch($num)
        {
            case '0':
                $request = '/home/index/index';
                break;
            case '1':
                $request .= '/index/index';
                break;
            case '2':
                $request .= '/index';
                break;
        }

        $matches = array();
        $temp = array();

        //匹配规则
        foreach(self::$routes as $v)
        {
            preg_match($v, $request, $temp);
            $temp ? $matches = $temp : '';
        }

        /**
        $matches => Array
            (
                [0] => /admin/index/index/id/1126
                [module] => admin
                [1] => admin
                [2] => admin
                [controller] => index
                [3] => index
                [4] => index
                [action] => index
                [5] => index
                [6] => index
                [7] => /1126
            )
         */
        if($matches)
        {
            foreach ($matches as $key => $value)
            {
                //除去数字key元素,保留关联元素。与上面的preg_match一起理解
                if(is_int($key))
                {
                    unset($matches[$key]);
                }
            }

            $result = $matches;

            //URL参数超过后的处理
            if($num > count($result))
            {
                /**
                $arguments => Array
                    (
                        [1] => admin
                        [2] => index
                        [3] => index
                        [4] => id
                        [5] => 1126
                    )
                 */
                $i = 1;
                foreach ($arguments as $k => $v)
                {
                    if($k > sizeof($result))
                    {
                        if($i == 1) {
                            $result[$v] = '';
                            $temp = $v;
                            $i = 2;
                        } else {
                            $result[$temp] = $v;
                            $i = 1;
                        }
                    }
                }
            }

            /**
            $result => Array
                (
                    [module] => admin
                    [controller] => index
                    [action] => index
                    [id] => 1126
                )
             */

            //保存参数部分
            $parts = $result;
            if(isset($parts['module'])){
                unset($parts['module']);
            }
            if(isset($parts['controller'])){
                unset($parts['controller']);
            }
            if(isset($parts['action'])){
                unset($parts['action']);
            }

            if($parts) {
                self::$parts = $parts;
            }

            //返回module、controller、action
            return $result;
        }

        return false;
    }

    /**
     * Note: 获取URL参数
     * User: Eleven
     * Date: 2018/9/16 1:05
     * @return array
     */
    public static function getParts()
    {
        return self::$parts;
    }

}