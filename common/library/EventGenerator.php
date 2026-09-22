<?php
/**
 * 观察者模式
 * 场景：一个事件发生后,要执行一连串更新操作.传统的编程方式,就是在事件的代码之后直接加入处理逻辑,
 * 当更新得逻辑增多之后,代码会变得难以维护.这种方式是耦合的,侵入式的,增加新的逻辑需要改变事件主题的代码
 * 观察者模式实现了低耦合,非侵入式的通知与更新机制
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-17
 * Time: 17:15
 */

namespace lib;

use App\admin\core\BaseController;

/**
 * 事件产生类
 * Class EventGenerator
 * @package App\admin\controller
 */
abstract class EventGenerator extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var array 观察者
     */
    private $objServers = [];

    /**
     * Note：注册观察者
     * User：Eleven
     * Date：2019-1-17 17:16
     * @param ObServer $obServer
     */
    public function addObj(ObServer $obServer)
    {
        $this->objServers[] = $obServer;
    }

    /**
     * Note：移除观察者
     * User：Eleven
     * Date：2019-1-21 18:00
     * @param ObServer $objServer
     */
    public function removeObj(ObServer $objServer)
    {
        foreach ($this->objServers as $key => $obj) {
            if ($objServer == $obj){
                unset($this->observers[$key]);
            }
        }
    }

    /**
     * Note：事件通知
     * User：Eleven
     * Date：2019-1-17 17:17
     */
    public function notify()
    {
        foreach ($this->objServers as $objServer) {
            $objServer->ObUpdate();
        }
    }
}