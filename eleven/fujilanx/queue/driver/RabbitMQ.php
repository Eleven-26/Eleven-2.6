<?php
/**
 * Created by PhpStorm.
 * Note: Rabbit 队列
 * User: chenkangfu
 * Date: 2018/9/29
 * Time: 0:50
 */

namespace fuji\queue\driver;
use fuji\Config;


/**
 * Class RabbitMQ
 * @package fuji\queue
 */
class RabbitMQ
{

    //RabbitMQ 链接
    private static $conn = null;
    //交换机
    private static $exchange = null;
    //队列
    private static $queue = null;
    //配置
    private static $config = [];
    //交换机
    private static $exchange_name;
    //队列
    private static $queue_name;
    //路由键
    private static $route_key;
    //是否持久化
    private static $durable = true;


    /**
     * RabbitMQ constructor.
     * @param array $config 配置
     * @param string $exchange_name 交换机
     * @param string $queue_name 队列
     * @param string $route_key 路由键
     * @param bool $durable     是否持久化
     */
    public function __construct($exchange_name = '', $queue_name = '', $route_key = '', $durable = true, array $config = [])
    {
        //获取配置
        if (empty($config)) {
            $config = Config::getConfig('rabbitmq');
        }
        self::$config = $config;
        self::$exchange_name = $exchange_name;
        self::$queue_name = $queue_name;
        self::$route_key = $route_key;
        self::$durable = $durable;
    }

    /**
     * Note: 初始化 RabbitMQ
     * User: Eleven
     * Date: 2018/9/29 1:06
     */
    public function init()
    {
        //创建连接
        self::$conn = new \AMQPConnection(self::$config);
        if (!self::$conn->connect()) {
            //抛出异常
            throw new \AMQPConnectionException('Cannot connect to the broker!');
        }

        //创建channel
        $channel = new \AMQPChannel(self::$conn);

        //创建交换机
        self::$exchange = new \AMQPExchange($channel);
        //交换机名
        self::$exchange->setName(self::$exchange_name);
        /**
         * 交换机类型
         * Direct exchange（直连交换机）
         * Fanout exchange（扇型交换机）
         * Topic exchange（主题交换机）
         * Headers exchange（头交换机）
         */
        self::$exchange->setType(AMQP_EX_TYPE_DIRECT);//Direct
        //交换机持久化
        if (self::$durable) {
            self::$exchange->setFlags(AMQP_DURABLE);
        }
        //声明交换机
        self::$exchange->declareExchange();

        //创建队列
        self::$queue = new \AMQPQueue($channel);
        //队列名
        self::$queue->setName(self::$queue_name);
        //队列持久化
        if (self::$durable) {
            self::$queue->setFlags(AMQP_DURABLE);
        }
        //声明队列
        self::$queue->declareQueue();

        //绑定交换机与队列，并指定路由键
        self::$queue->bind(self::$exchange_name, self::$route_key);
    }

    /**
     * Note: 生产者发送消息
     * User: Eleven
     * Date: 2018/9/29 1:41
     * @param string $msg
     * @throws \AMQPConnectionException
     */
    public function send($msg = '')
    {
        //获取连接
        $this->init();
        //发送消息
        $res = self::$exchange->publish($msg, self::$route_key);

        return $res;
    }

    /**
     * Note: 消费者接收消息
     * User: Eleven
     * Date: 2018/9/29 1:44
     * @param $fun_name             array($classobj, $function) or function name string
     * @param bool|true $autoack    是否自动应答
     * @return bool
     * @throws \AMQPConnectionException
     */
    public function consumer($fun_name, $autoack = true)
    {
        //获取连接
        $this->init();

        if (!$fun_name || !self::$queue) {
            return False;
        }
//        $i = 0;
        while(True){
            if ($autoack) {
                self::$queue->consume($fun_name, AMQP_AUTOACK);
            } else {
                self::$queue->consume($fun_name);
            }

            //测试执行五次
//            $i ++;
//            if ($i >= 5) {
//                break;
//            }
        }

        //断开连接
        self::$conn->disconnect();
    }

    /**
     * Note: 消费回调函数，处理消息
     * User: Eleven
     * Date: 2018/9/30 0:58
     * @param $envelope
     * @param $queue
     */
    public function callback($envelope, $queue)
    {
        //获取消息
        $msg = $envelope->getBody();

        //以下为处理消息逻辑
        echo ($msg) . "<br>";

        //手动发送ACK应答
        $queue->ack($envelope->getDeliveryTag());
    }

}
