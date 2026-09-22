<?php
/**
 * 框架初始化文件
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 23:36
 */

//载入常量配置文件
require_once(ELEVEN_PATH ."base.php");

//加载辅助函数库
require_once(ELEVEN_PATH ."helper.php");

//应用初始化
\fuji\App::run();




