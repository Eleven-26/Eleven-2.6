<?php
/**
 * APP接口
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/9
 * Time: 0:01
 */

namespace App\api\mobile;


use App\api\core\BaseController;
use fuji\Api;

class mobile extends BaseController
{
    /**
     * mobile constructor.
     */
    public function __construct()
    {
        //TODO 安全验证
    }

    /**
     * Note: 测试
     * User: Eleven
     * Date: 2018/10/20 16:00
     */
    public function test()
    {
        $data = [
            'id' => 1126,
            'name' => 'Eleven',
            'time' => '2018/10/20 15:08',
            'ids' => [1,2,26,11]
        ];
        $code = '200';
//        $type = 'json';
        $type = 'xml';
//        $type = 'array';

        Api::response($data, $code, $type);
    }

}