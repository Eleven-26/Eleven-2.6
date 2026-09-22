<?php
/**
 * 请求框架唯一入口文件
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 23:19
 */

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 定义系统核心目录
define('ELEVEN_PATH', __DIR__ . '/../eleven/');

// 加载框架初始化文件
require __DIR__ . '/../eleven/initial.php';