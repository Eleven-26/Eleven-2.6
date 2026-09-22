<?php
/**
 * 后台首页控制器
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/8/27
 * Time: 23:58
 */

namespace App\admin\controller;

use App\admin\core\BaseController;
use App\admin\models\User;
use fuji\Db;
use fuji\Log;
use fuji\Jwt;
use Seaslog;

/**
 * Class Index
 * @package APP\admin\controller
 */
class Index extends BaseController
{

    /**
     * Index constructor.
     */
    public function __construct()
    {

    }

    /**
     * @Introduce：后台首页
     * @Author：Eleven
     * @Date：2018/11/26 00:11:26
     * @return string
     */
    public function index()
    {

//        $number = 2;
//        $str = "Shanghai";
//        $txt = sprintf("There are %d million cars in %s.",$number,$str);
////        echo $txt;exit;
//        $a=array("Volvo"=>"XC90","BMW"=>"X5","Toyota"=>"Highlander");
//        print_r(array_keys($a));
//        print_r(array_values($a));
//        exit;
//        $firstname = "Bill";
//        $lastname = "Gates";
//        $age = "60";
//
//        $result = compact("firstname", "lastname", "age");
//
//        print_r($result);
//        exit;

//        $a = Array
//        (
//            0 => ':name)',
//            1 => ':name,',
//            2 => ':name' ,
//            3 => ':name'
//        );
//
//        $b = Array
//        (
//            0 => '\'Eleven11\'),',
//            1 => '\'Eleven11\',',
//            2 => '\'Eleven11\'',
//            3 =>' \'Eleven11\'',
//
//        );
//        $sql = 'insert into admin_user(`name`,`pwd`,`email`,`mobile`) VALUE (:name,:pwd,:email,:mobile)';
//
//        $sql = str_replace($a,$b,$sql);
//        echo $sql;exit;


//        $result = Db::query("insert into admin_user(`name`,`pwd`,`email`,`mobile`) VALUE (:name,:pwd,:email,:mobile)",[
//            'name' => ['Eleven11',\PDO::PARAM_STR],
//            'pwd' => '222222',
//            'email' => '649427003@qq.com',
//            'mobile' => [15678822646,\PDO::PARAM_INT],
//        ]);

//        $result = Db::query("select * from admin_user where id >= :id",[
//            'id' => [0,\PDO::PARAM_INT]
//        ]);

//        $result = '<p>也有很多开源<code>log</code>类库弥补了上述缺陷，如<code>log4php</code>、<code>plog</code>、<code>Analog</code>等(当然也有很多应用在项目中自己开发的<code>log</code>类)。其中以<code>log4php</code>最为著名，它的设计精良、格式完美、文档完善、功能强大。但是经过测试，<code>log4php</code>的性能非常差。</p>
//<p>而<code>sealog</code>在性能上有着强大的优势，假设一个请求中需要写出1000处log，那么势必会有1000次IO，这对性能将是一个很大的拖延点。一般对于处理这种多次相同的请求场景，我们要解决的其实也很简单，使用cache或buffer，把多次请求作归并，从而降低对磁盘或网络的IO，这是一个基本的思想。</p>
//<p><code>SeasLog</code>也是这么做的。设定一个<code>buffer_size</code>(默认<code>100</code>条<code>log</code>)，使用<code>PHP</code>请求内存，每写一次<code>log</code>，塞入内存，同时<code>buffer_size</code>加;当<code>buffer_size</code>等于设置值时，则进行一次<code>IO</code>，同时清除<code>buffer</code>; 当然，如果请求结束了、或执行了<code>die</code>、<code>exit</code>或其他异常退出时，不管<code>buffer_size</code>有没有攒够设置值，立刻进行一次<code>IO</code>，同时清除<code>buffer</code>。</p>';
//        Eleven($result);

//        Db::table('admin_user');
//        Log::writeLog('info/test',array(11,22,55555));
//        Log::writeLog('info/test','护肤和东方红复健科司法鉴定所54545');

//        echo Seaslog::getBasePath();
//        echo Seaslog::getBasePath();


//        $result  = User::getUser();
//        Eleven($result);

    }

    /**
     * Note: 注册会员
     * User: Eleven
     * Date: 2018/9/16 22:26
     */
    public function add()
    {
        $user = new User();
        echo $user->addUser();
    }

}