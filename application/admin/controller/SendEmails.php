<?php
/**
 * 邮件
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-30
 * Time: 11:28
 */

namespace App\admin\controller;


use App\admin\core\BaseController;
use lib\NoticeMode;
use fuji\Config;

class SendEmails extends BaseController implements NoticeMode
{
    /**
     * @var null 配置
     */
    private $config = null;

    public function __construct(array $config = [])
    {
        parent::__construct();

        //不传配置则获取配置文件的配置
        if (empty($config)){
            $config = Config::getConfig('sendEmails');
        }

        $this->config = $config;
    }

    /**
     * Note：发邮件
     * User：Eleven
     * Date：2019-5-30 11:29
     * @param $params
     */
    public function toNotice(array $params)
    {
        // TODO: Implement toNotice() method.
        echo '邮件';
        Eleven($params);
    }

}