<?php
/**
 * 测试容器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 16:22
 */

namespace App\admin\controller;


use App\admin\core\BaseController;

class Dog extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Note：
     * User：Eleven
     * Date：2019-5-29 16:22
     * @return string
     */
    public function dogCall(){
        return '汪汪汪';
    }

}