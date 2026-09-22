<?php
/**
 * 接口公共控制器
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-4-24
 * Time: 10:00
 */

namespace App\api\core;


use fuji\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

}