<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 17:48
 */

namespace lib;

/**
 * 订单状态code
 * Class OrderStatusCode
 * @package lib
 */
class OrderStatusCode
{
    const ORDER_WAIT = 11;      //待支付
    const ORDER_PAY = 12;       //已支付

    public static $orderStatusCode = array(
        self::ORDER_WAIT => '待支付',
        self::ORDER_PAY => '已支付',
    );

}