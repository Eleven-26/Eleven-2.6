<?php
/**
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/9
 * Time: 13:30
 */

//定义系统常量
@define('ENVIRONMENT', 'production');///环境类型：production,development
define('EXT', '.php');//PHP文件后缀
define('DS', DIRECTORY_SEPARATOR);//目录分隔符 "/"
defined('FUJI_PATH') or define('FUJI_PATH',  ELEVEN_PATH . 'fujilanx'.DS); // 核心类库目录
defined('CONF_PATH') or define('CONF_PATH',  __DIR__ . '/../common/config'.DS); // 配置文件目录
defined('HELPER_PATH') or define('HELPER_PATH',  __DIR__ . '/../common/helper'.DS); // 辅助函数文件目录
defined('LIB_PATH') or define('LIB_PATH',  __DIR__ . '/../common/library'.DS); // 基础类
defined('MODEL_PATH') or define('MODEL_PATH',  __DIR__ . '/../common/models'.DS); // 模型
defined('RUNTIME_PATH') or define('RUNTIME_PATH',  __DIR__ . '/../runtime'.DS); // 临时文件目录
defined('LOG_PATH') or define('LOG_PATH',  __DIR__ . '/../runtime/log'.DS); // 临时文件目录
defined('CACHE_PATH') or define('CACHE_PATH',  __DIR__ . '/../runtime/cache'.DS); // 缓存文件目录
defined('VENDOR_PATH') or define('VENDOR_PATH',  __DIR__ . '/../vendor'.DS); // 第三方包

//加载自动注册类
require FUJI_PATH . 'Loader.php';

//注册自动加载
\fuji\Loader::register();

//注册路由规则
\fuji\Url::addRoute();
