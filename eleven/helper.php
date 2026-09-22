<?php
/**
 * 系统助手函数
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 23:40
 */

if (!function_exists('debug')) {
    /**
     * 记录时间（微秒）和内存使用情况
     * @param string            $start 开始标签
     * @param string            $end 结束标签
     * @param integer|string    $dec 小数位 如果是m 表示统计内存占用
     * @return mixed
     */
    function debug($start, $end = '', $dec = 6)
    {
        if ('' == $end) {
            Debug::remark($start);
        } else {
            return 'm' == $dec ? Debug::getRangeMem($start, $end) : Debug::getRangeTime($start, $end, $dec);
        }
    }
}

if (!function_exists('Eleven')) {
    /**
     * 输出内容
     * @param $value
     */
    function Eleven($value)
    {
        if(is_array($value)){
            echo "<pre>";print_r($value);
        }elseif(is_string($value)){
            echo $value;
        }else{
            var_dump($value);
        }
        exit;
    }
}