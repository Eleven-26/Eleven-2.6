<?php
/**
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/8/28
 * Time: 0:08
 */

namespace com\models;

use fuji\Config;
use mod\BaseModel;


/**
 * Class UserModel
 * @package com\models
 */
class UserModel extends BaseModel
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note: 获取用户信息
     * User: Eleven
     * Date: 2018/9/16 22:22
     * @return array|string
     */
    public static function getUser()
    {
        $config = Config::getConfig();
        return $config;
        return 'User::getUser';
    }

    /**
     * Note: 添加用户
     * User: Eleven
     * Date: 2018/9/16 22:22
     * @return string
     */
    public function addUser()
    {
        return 'User->assUser';
    }

}