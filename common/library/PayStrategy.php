<?php
/**
 * 策略模式：方法根据传入对象参数的不同,而具有不同的响应行为的一种设计模式。
 * 它定义了算法家族，分别封装起来，让它们之间可以互相替换，此模式让算法的变化，
 * 不会影响到使用算法的客户。
 * 策略模式是一种定义一些列算法的方法，从概念上来看，所有这些算法完成的都是
 * 相同的工作，只是实现不同，它可以以相同的方式调用所有的算法，减少了各种算法类
 * 与使用算法类的耦合。
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 11:35
 */

namespace lib;

/**
 * 抽象支付策略接口
 * Interface Strategy
 * @package lib
 */
interface PayStrategy
{
    public function gotoPay($order = []);
}