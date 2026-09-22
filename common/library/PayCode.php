<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 15:06
 */

namespace lib;

/**
 * 支付方式code
 * Class PayCode
 * @package lib
 */
class PayCode
{
    const ALI_PAY = 101;        //支付宝
    const WEIXIN_PAY = 102;     //微信

    public static $payCode = array(
        self::ALI_PAY => '支付宝',
        self::WEIXIN_PAY => '微信支付',
    );

}