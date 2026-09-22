<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-17
 * Time: 17:17
 */

namespace lib;

/**
 * 观察者接口
 * Interface ObServer
 * @package App\admin\controller
 */
interface ObServer
{
    public function ObUpdate($event_info = []);
}