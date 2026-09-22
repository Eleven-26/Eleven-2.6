<?php
/**
 * 通知接口，如发短信、发邮件
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-30
 * Time: 11:20
 */

namespace lib;


interface NoticeMode
{
    public function toNotice(array $params);
}