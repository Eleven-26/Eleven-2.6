<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 11:39
 */

namespace App\admin\controller;

use App\admin\core\BaseController;
use lib\PayStrategy;

/**
 * 微信支付策略
 * Class WxPay
 * @package App\admin\controller
 */
class WxPay extends BaseController implements PayStrategy
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note：
     * User：Eleven
     * Date：2019-1-21 11:40
     * @param array $order
     */
    public function gotoPay($order = [])
    {
        // TODO: Implement charge() method.
    }

}