<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-17
 * Time: 17:21
 */

namespace App\admin\controller;

use App\admin\core\BaseController;
use lib\ObServer;

class ObServer2 extends BaseController implements ObServer
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note：
     * User：Eleven
     * Date：2019-1-17 17:22
     * @param array $event_info
     */
    public function ObUpdate($event_info = [])
    {
        // TODO: Implement update() method.
        echo "观察者2 收到执行通知 执行完毕！\n";
    }

}