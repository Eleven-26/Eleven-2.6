<?php
/**
 * Created by PhpStorm.
 * Note: API 接口基类
 * User: chenkangfu
 * Date: 2018/10/20
 * Time: 14:07
 */

namespace fuji;


/**
 * Class Api
 * @package fuji
 */
class Api
{

    /**
     * Note: api 返回数据
     * User: Eleven
     * Date: 2018/10/20 14:35
     * @param array $array  返回的数据
     * @param int $code 状态码
     * @param string $type  返回数据类型，支持json、xml、array
     */
    public static function response($array = [], $code = 200, $type = 'json')
    {

        //返回数据类型不支持
        if (!in_array($type,['json','xml','array'])) {

            $data = [
                'error_code' => '202',
                'error_msg'  => 'return dataType error',
                'data'       => []
            ];
        } elseif (!is_numeric($code)) {//状态码错误

            $data = [
                'error_code' => '201',
                'error_msg'  => 'Parameter code error',
                'data'       => []
            ];
        } else {

            //根据code返回msg
            $msg = '返回成功';

            $data = [
                'error_code'  => $code,
                'error_msg'   => $msg,
                'data'        => $array,
            ];
        }

        //转换小写
        $type = strtolower($type);

        //返回数据类型
        if ($type == 'json') {
            $output = self::json($data);
        } elseif ($type == 'xml') {
            $output = self::xml($data);
        } elseif ($type == 'array') {
            //打印数据，调试
            Eleven($data);
        }

        exit($output);
    }

    /**
     * Note: 返回json数据
     * 该函数只能接受UTF-8编码的数据，如果传递其他格式的数据该函数会返回null
     * User: Eleven
     * Date: 2018/10/20 14:59
     * @param $data
     * @return string
     */
    private static function json($data)
    {
        //返回json格式
        header('Content-Type:text/json;charset=utf-8');

        return json_encode($data);
    }

    /**
     * Note: 返回XML数据
     * User: Eleven
     * Date: 2018/10/20 14:59
     * @param $data
     * @return string
     */
    private static function xml($data)
    {
        //返回XML格式
        header('Content-Type:text/xml;charset=utf-8');

        //XML头信息
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= self::xmlToEncode($data);
        $xml .= "</root>";

        return $xml;
    }

    /**
     * Note: 组装XML数据
     * User: Eleven
     * Date: 2018/10/20 15:33
     * @param $data
     * @return string
     */
    private static function xmlToEncode($data)
    {
        $xml = '';
        $attr = '';
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                //XML不允许数字的key，当成属性处理
                if (is_numeric($key)) {
                    $attr = " id='{$key}'";
                    $key = 'item';
                }
                $xml .= "<{$key}{$attr}>";

                //数组需要递归
                if (is_array($value)) {
                    $xml .= self::xmlToEncode($value);
                } else {
                    $xml .= $value;
                }
                $xml .= "</{$key}>\n";
            }
        }

        return $xml;
    }

    /**
     * Note: 以curl方式调用外部接口
     * User: Eleven
     * Date: 2018/10/20 16:14
     * @param $url
     * @param $param
     * @param bool|true $post
     */
    public static function curlApi($url, $param, $post = true)
    {
    }
}
