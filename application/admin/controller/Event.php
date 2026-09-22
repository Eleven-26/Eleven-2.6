<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-17
 * Time: 17:21
 */

namespace App\admin\controller;

use lib\EventGenerator;

class Event extends EventGenerator
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note：触发事件
     * User：Eleven
     * Date：2019-1-17 17:22
     */
    public function trigger()
    {
        //通知观察者
        $this->notify();
    }

}