<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 17:48
 */

namespace lib;

/**
 * 订单日志code
 * Class OrderLogStatusCode
 * @package lib
 */
class OrderLogStatusCode
{
    const ORDER_LOG_CREAT = 101;        //订单创建
    const ORDER_LOG_PAY = 102;          //订单支付

    public static $orderLogStatusCode = array(
        self::ORDER_LOG_CREAT => '订单创建',
        self::ORDER_LOG_PAY => '订单支付',
    );
}