<?php
/**
 * Created by PhpStorm.
 * Note: 测试
 * User: chenkangfu
 * Date: 2018/9/14
 * Time: 20:48
 */

namespace App\admin\controller;


use fuji\Aes;
use fuji\cache\driver\Memcache;
use fuji\cache\driver\Redis;
use fuji\Config;
use fuji\Controller;
use App\admin\models\User;
use fuji\Db;
use fuji\Log;
use fuji\queue\driver\RabbitMQ;
use lib\PayCode;
use Seaslog;
use lib\sphinx\SphinxClient;
use fuji\Jwt;
use fuji\Rsa;
use lib\Container;

class test extends Controller
{
    /**
     * Note: 请填写说明
     * User: Eleven
     * Date: 2018/9/14 23:22
     * @return mixed
     * @internal param $a
     * @internal param string $b
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
//        echo Log::writeLog('fdfjhhf',[
//            'id' => 1126,
//            'name' => 'Eleven',
//            'time' => '2018-09-24 00:11:26'
//        ],'info');

//        print_r(\Seaslog::analyzerCount());echo "<hr>";
//        print_r(\Seaslog::analyzerCount());echo "<hr>";
//        print_r(\Seaslog::analyzerDetail('all',date('Ymd',time())));echo "<hr>";
//
//        echo Log::writeLog('发的发货靠很快乐健康了解了解','DEBUG');echo "<hr>";
//        $res = Log::getLog('DEBUG');
//        print_r($res);echo "<hr>";

//        \Seaslog::log('fdfjhhf',[
//            'id' => 1126,
//            'name' => 'Eleven',
//            'time' => '2018-09-24 00:11:26'
//        ],'info','admin');
//        echo Log::getBasePath();

//        $memcache = new \Memcache();
//        $memcache->addServer('127.0.0.1',11211);

//        $memcache->connect($config);
//        Eleven($memcache);
//        $memcache->set('aa',4545);
//        $res = $memcache->get('aa');
//        var_dump($res);
//
//        $mem = new Memcache([
//            'host' => '127.0.0.1',
//            'port' => 11211
//        ]);
//        $mem->set('key','1112',600);
//        $res = $mem->get('key');
////        $mem->del('key');
//        $res = $mem->get('key');
//        var_dump($res);

        //连接主服务器
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1', 6379);
//        $redis->auth(1126);
//        $res = $redis->info();
//
//        $redis->set('kk','kwy2');
//        $res = $redis->get('kk');
//
//        Eleven($res);
//        $config = Config::getConfig('redis');
//        $redis = new Redis($config, true);
//        $redis->set('key','54848',600);
//        $res = $redis->get('key');
//        Eleven($res);

//        $rabbitMQ = new RabbitMQ('exchange_test','queue_test','route_test');
//        $res = $rabbitMQ->send('我是测试消息');
//        $res = $rabbitMQ->send('我是测试消息');
//        $res = $rabbitMQ->send('我是测试消息');
//        $res = $rabbitMQ->consumer([$rabbitMQ, 'callback'], false);
//        var_dump(7777);
//        $table = 'admin_user';
//        $where = ['id' => 1126];
//        $whereOr = ['name' => 'Eleven'];
//        $defined_vars = get_defined_vars();
//        $res = $this->phpInfo();
//        Eleven($res);
////        include VENDOR_PATH . 'sphinx/sphinx.php';
//        $keyword = 'test';
//        $sphinx = new SphinxClient();
//        $sphinx->SetServer('localhost', 9312);
//        $sphinx->setMatchMode(SPH_MATCH_ANY);//匹配模式 SPH_MATCH_ALL：完全匹配
//        $sphinx->SetArrayResult(true);//匹配模式 SPH_MATCH_ALL：完全匹配
//        $result = $sphinx->Query($keyword, '*');
////        Eleven($result);
//
//        //获取匹配结果的ID
//        $ids = '';
//        foreach ($result['matches'] as $value) {
//            if ($ids) {
//                $ids .= ',';
//            }
//            $ids .= $value['id'];
//        }
////        $ids = implode(',',array_keys($result['matches']));
//
//        //链接数据库并查询匹配的id记录
//        $conn = mysqli_connect('localhost','root','root');
//        mysqli_query($conn,'set names utf8');
//        mysqli_select_db($conn,'eleven');
//        $sql = "select * from documents where id in (".$ids.")";
//        $rst = mysqli_query($conn,$sql);
//
//        //给匹配关键字添加样式
//        $opts = array(
//            'before_match' => '<font style="font-weight:bold;color:#f00;">','after_match'=>'</font>'
//        );
//
//        while ($row = mysqli_fetch_assoc($rst)) {
//            $row2[] = $sphinx->buildExcerpts($row,'test1',$keyword,$opts);//test1 配置文件中的主数据源索引print_r($row2);
//        }
//
//        Eleven($row2);

        //创建一个事件
//        $event = new Event();
//        //为事件增加观察者
//        $event->addObj(new ObServer1());
//        $event->addObj(new ObServer2());
//        //执行事件 通知观察者
//        $event->trigger();

        // 获取用户选择的支付方式
        $pay_code = PayCode::ALI_PAY;
        $order_info = [];

        $pay = new Pay($pay_code);
        // 初始化支付实例
//        $pay->initInstance($pay_code);

        // 调用支付
        $pay->gotoPay($order_info);
    }

    /**
     * Note：jwt 测试
     * User：Eleven
     * Date：2019-4-24 10:06
     */
    public function jwt()
    {
        //测试和官网是否匹配begin
        $payload = array('sub'=>'1234567890','name'=>'John Doe','iat'=>1516239022);

        $token = Jwt::getToken($payload);
        echo "<pre>";
        echo 'TOKEN:' . $token;

        //对token进行验证签名
        $getPayload = Jwt::verifyToken($token);
        echo "<br><br>";
        var_dump($getPayload);
        echo "<br><br>";
        //测试和官网是否匹配end

        //自己使用测试begin
        $payload_test = array('iss'=>'admin','iat'=>time(),'exp'=>time()+7200,'nbf'=>time(),'sub'=>'www.admin.com','jti'=>md5(uniqid('JWT').time()));;
        $token_test = Jwt::getToken($payload_test);
        echo "<pre>";
        echo $token_test;

        //对token进行验证签名
        $getPayload_test = Jwt::verifyToken($token_test);
        echo "<br><br>";
        var_dump($getPayload_test);
        echo "<br><br>";
        //自己使用时候end
    }

    /**
     * Note：rsa 测试
     * User：Eleven
     * Date：2019-4-28 11:36
     */
    public function rsa()
    {

        $public_key = file_get_contents('public.txt');//公钥
        $private_key = file_get_contents('private.txt');//私钥
        $rsa = new Rsa($public_key,$private_key);
//
        $str = '这里是待加密的数据';
        echo '<hr>公钥加密私钥解密如下:<hr>';
        echo '原始数据:',$str,'<br>';
        $tmpstr = $rsa->public_encrypt($str); //用公钥加密
        echo '加密后的数据:' . $tmpstr,'</br>';
        $tmpstr = $rsa->private_decrypt($tmpstr); //用私钥解密
        echo '解密结果:' . $tmpstr,'<hr>私钥加密公钥解密如下:<hr>';
////=============================================================

        echo '原始数据:',$str,'<br>';
        $tmpstr = $rsa->private_encrypt($str); //用私钥加密
        echo '加密后的数据' . $tmpstr,'</br>';
        $tmpstr = $rsa->public_decrypt($tmpstr); //用公密解密
        echo '解密结果:' . $tmpstr,'</br>';

///==========================签名验证===================
        $str = '11223344';
        echo '待签名的数据是' . $str . '<hr>';
        $obj = new Rsa();
        $sign = $obj->rsaSign($str,$private_key);

        echo '签名后的数据是' . $sign . '<hr>';

        if($obj->rsaCheck($str,$public_key,$sign)){
            echo '验证成功';
        }else{
            echo '验证失败';
        }
    }

    /**
     * Note：aes 测试
     * User：Eleven
     * Date：2019-4-28 16:27
     */
    public function aes()
    {
        $aes = new Aes('12345678');

        $encrypted = $aes->encrypt('bbm是一家很傻逼的公司');

        echo '要加密的字符串：bbm是一家很傻逼的公司<br>加密后的字符串：', $encrypted, '<hr>';

        $decrypted = $aes->decrypt($encrypted);

        echo '要解密的字符串：', $encrypted, '<br>解密后的字符串：', $decrypted;
    }

    /**
     * Note：Container 测试
     * User：Eleven
     * Date：2019-5-29 16:24
     */
    public function container()
    {
        //实例化容器类
        $app =  new Container();

        //向容器中填充Dog
//        $app->bind('App\admin\controller\Dog');

        //填充People
//        $app->bind('App\admin\controller\People');

        //通过容器实现依赖注入，完成类的实例化；
        $people = $app->make('App\admin\controller\People');

        //调用方法
        echo $people->putDog();
    }

    /**
     * Note：通知测试
     * User：Eleven
     * Date：2019-5-30 11:40
     */
    public function send()
    {
        //实例化容器
//        $app = new Container();
//
//        //注册短信类
////        $app->bind('App\admin\controller\SendMessage');
//        $app->bind('App\admin\controller\SendEmails');
//
//        $app->bind('App\admin\controller\Send');
//
//        //通过容器实现依赖注入
//        $send = $app->make('App\admin\controller\Send');

        //注入发短信对象
        $send = new Send(new SendMessage());

        //注入发邮件对象
//        $send = new Send(new SendEmails());
        //调用发送通知方法
        $params = [
            'name' => '主题',
            'content' => '内容',
        ];

        $send->send($params);
    }

    /**
     * Note: 输出PHP信息
     * User: Eleven
     * Date: 2018/9/25 1:55
     */
    public function phpInfo()
    {
        echo phpinfo();
    }

}
