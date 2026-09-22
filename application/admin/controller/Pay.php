<?php
/**
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-1-21
 * Time: 11:51
 */

namespace App\admin\controller;

use App\admin\core\BaseController;
use lib\PayCode;
use lib\PayStrategy;

/**
 * 支付环境角色，客户端调用
 * Class Pay
 * @package App\admin\controller
 */
final class Pay extends BaseController
{
    /**
     * @var null 支付类型模型
     */
    private $payObj = null;

    public function __construct(PayStrategy $pay_code)
    {
        parent::__construct();

        //获取支付方式，简单工厂与策略模式混合使用
        switch ($pay_code){
            case PayCode::ALI_PAY:
                //支付宝
                $this->payObj = new AliPay();
                break;
            case PayCode::WEIXIN_PAY:
                //微信支付
                $this->payObj = new WxPay();
                break;
            default:
                //参数错误
                $this->payObj = null;
                break;
        }
    }

    /**
     * Note：获取支付方式，简单工厂与策略模式混合使用
     * User：Eleven
     * Date：2019-1-21 16:48
     * @param $pay_code
     */
//    public function initInstance($pay_code)
//    {
//        switch ($pay_code){
//            case PayCode::ALI_PAY:
//                //支付宝
//                $this->payObj = new AliPay();
//                break;
//            case PayCode::WEIXIN_PAY:
//                //微信支付
//                $this->payObj = new WxPay();
//                break;
//            default:
//                //参数错误
//                $this->payObj = null;
//                break;
//        }
//    }

    /**
     * Note：支付
     * User：Eleven
     * Date：2019-1-21 16:50
     * @param array $order
     */
    public function gotoPay($order = [])
    {
        if (is_null($this->payObj)){
            echo '初始化错误';
            exit;
        }

        $this->payObj->gotoPay($order);
    }
}