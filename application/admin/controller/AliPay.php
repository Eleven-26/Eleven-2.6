<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 11:37
 */

namespace App\admin\controller;

use App\admin\core\BaseController;
use lib\PayStrategy;

/**
 * 支付宝策略
 * Class AliCharge
 * @package App\admin\controller
 */
class AliPay extends BaseController implements PayStrategy
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note：
     * User：Eleven
     * Date：2019-1-21 11:39
     * @param array $order
     */
    public function gotoPay($order = [])
    {
        // TODO: Implement charge() method.
    }

}