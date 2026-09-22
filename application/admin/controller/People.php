<?php
/**
 * 测试容器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 16:20
 */

namespace App\admin\controller;


use App\admin\core\BaseController;

class People extends BaseController
{
    public $dog = null;

    public function __construct(Dog $dog)
    {
        parent::__construct();

        $this->dog = $dog;
    }

    /**
     * Note：
     * User：Eleven
     * Date：2019-5-29 16:22
     * @return string
     */
    public function putDog(){
        return $this->dog->dogCall();
    }
}