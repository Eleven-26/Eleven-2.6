<?php
/**
 * 短信
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-30
 * Time: 11:24
 */

namespace App\admin\controller;


use App\admin\core\BaseController;
use fuji\Config;
use lib\NoticeMode;

class SendMessage extends BaseController implements NoticeMode
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
            $config = Config::getConfig('sendMessage');
        }

        $this->config = $config;
    }

    /**
     * Note：发短信
     * User：Eleven
     * Date：2019-5-30 11:27
     * @param $params
     */
    public function toNotice(array $params)
    {
        // TODO: Implement toNotice() method.
        echo '短信';
        Eleven($params);
    }
}