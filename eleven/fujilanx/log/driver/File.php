<?php
/**
 * Created by PhpStorm.
 * Note: 文件日志驱动
 * User: chenkangfu
 * Date: 2018/9/23
 * Time: 15:51
 */

namespace fuji\log\driver;

/**
 * Class File
 * @package fuji\log\driver
 */
class File
{

    //日志文件路径
    private static $filePath = '';
    //文件后缀
    private static $ext = '.log';
    //日志文件名
    private static $fileName = '';
    //保存数据类型,json、array
    const CONTENT_TYPE = 'array';

    /**
     * File constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        self::$filePath = $config['filePath'];
    }

    /**
     * Note: 保存日志
     * User: Eleven
     * Date: 2018/9/23 16:33
     * @param $message
     * @param $type
     * @param string $module
     * @param array|string $content
     * @return int
     * @internal param $name
     */
    public function save($message, $type, $module, $content = array())
    {
        $name = '';
        //完整目录、文件名
        self::$fileName = self::$filePath . $name . self::$ext;

        //目录部分
        $directory = substr(self::$filePath . $name,0,strrpos(self::$filePath . $name, '/'));

        //没有目录的创建
        if(!is_dir($directory))
        {
            mkdir($directory, 0777);
        }

        //拼上时间、类型、换行
        if(self::CONTENT_TYPE == 'json')
        {
            $message = json_encode($message,true);
        } elseif(self::CONTENT_TYPE == 'array') {
            $message = var_export($message,true);
        }
//        $content = '[ ' . date('Y-m-d H:i:s',time()) . ' ] [' . $type . ' ] ' . var_export($content,true) . PHP_EOL;
        $content = '[ ' . date('Y-m-d H:i:s',time()) . ' ] [' . $type . ' ] ' . $message . PHP_EOL;

        //保存日志文件
        $result = file_put_contents(self::$fileName, $content, FILE_APPEND);

        return $result;
    }

    /**
     * Note: 获取日志
     * User: Eleven
     * Date: 2018/9/24 0:26
     * @param $type 类型
     * @return mixed
     */
    public function get($type)
    {
        if($type == null){
            //获取所有类型

        } else {
            //获取指定类型

        }

        $log = '';
        return $log;
    }

}
