<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-30
 * Time: 11:35
 */

namespace App\admin\controller;


use App\admin\core\BaseController;
use lib\NoticeMode;

class Send extends BaseController
{
    //发送信息方式对象
    private $noticeObj = null;

    public function __construct(NoticeMode $noticeMode)
    {
        parent::__construct();
        $this->noticeObj = $noticeMode;
    }

    /**
     * Note：发送通知
     * User：Eleven
     * Date：2019-5-30 11:39
     * @param array $params
     */
    public function send(array $params)
    {
        $str = '发送通知，方式为 ：';

        echo $str . $this->noticeObj->toNotice($params);
    }

}