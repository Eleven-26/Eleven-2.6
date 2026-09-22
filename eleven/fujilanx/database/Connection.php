<?php
/**
 * 数据库基础抽象基类
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 22:30
 */

namespace fuji\database;

use PDO;
use PDOStatement;

abstract class Connection
{

    /**
     * @var array 数据库连接ID 支持多个连接
     */
    protected $links = [];

    /**
     * @var PDOStatement 对象
     */
    protected $PDOStatement;

    /**
     * @var string 当前执行的SQL
     */
    protected  $queryStr = '';

    /**
     * @var int 返回或者影响记录数
     */
    protected $numRows = 0;

    /**
     * @var int 查询结果类型:返回一个索引为结果集列名的数组
     */
    protected $fetchType = PDO::FETCH_ASSOC;

    /**
     * @var array 参数绑定
     */
    protected $bind = [];

    /**
     * @var 使用Builder类
     */
    protected $builder;

    /**
     * @var 当前链接数据库序号
     */
    protected $linkID;
    /**
     * @var
     */
    protected $linkRead;
    /**
     * @var
     */
    protected $linkWrite;

    /**
     * @var array 数据库连接参数配置
     */
    protected $config = [
        //数据库调试模式
        'debug'           => false,
        // Builder类
        'builder'         => '\\fuji\\database\\builder\\Mysql',
        // Query类
        'query'           => '\\fuji\\database\\Query',
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy'          => 0,
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
    ];

    //PDO链接参数
    protected $options = [
        PDO::ATTR_CASE              => PDO::CASE_NATURAL,       //强制列名为指定的大小写：保留数据库驱动返回的列名
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,  //错误模式：抛出 exceptions 异常
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,       //转换 NULL 和空字符串：不转换
        PDO::ATTR_STRINGIFY_FETCHES => false,                  //提取的时候将数值转换为字符串
        PDO::ATTR_EMULATE_PREPARES  => false,                  //启用或禁用预处理语句的模拟
    ];

    /**
     * Mysql constructor.
     * @param $config
     */
    public function __construct(array $config = array())
    {
        if (!empty($config)) {
            //配置属性覆盖
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * Note: 初始化数据库链接
     * User: Eleven
     * Date: 2018/9/20 1:08
     * @param bool|true $master 是否主服务器
     */
    protected function initConnect($master = true)
    {
        if($this->config['deploy']){
            // 采用分布式数据库

        } elseif (!$this->linkID){
            $this->linkID = $this->connect();
        }
    }

    /**
     * Note: 获取新的查询对象
     * User: Eleven
     * Date: 2018/9/18 2:30
     * @return mixed
     */
    protected function getQuery()
    {
        $class = $this->config['query'];
        return new $class($this);
    }

    /**
     * Note: 获取当前连接器类对应的Builder类
     * User: Eleven
     * Date: 2018/9/18 14:52
     * @return 使用Builder类|string
     */
    public function getBuilder()
    {
        if (!empty($this->builder)) {
            return $this->builder;
        } else {
            return $this->config['builder'];
        }
    }

    /**
     * Note: 数据库链接
     * User: Eleven
     * Date: 2018/9/18 14:59
     * @param array $config 链接配置
     * @param int $linkNum 连接序号
     * @param bool|false $autoConnection 是否自动连接主数据库（用于分布式）
     * @return mixed
     */
    public function connect(array $config = [], $linkNum = 0, $autoConnection = false)
    {
        if(!isset($this->links[$linkNum])) {

            try {

                if (!$config) {
                    $config = $this->config;
                } else {
                    //配置属性覆盖
                    $config = array_merge($this->config, $config);
                }

                //配置为空抛出异常
                if(empty($config)){
                    throw new \Exception('请配置数据库');
                }

                //PDO链接数据库

//                $dsn = 'mysql:host=127.0.0.1;dbname=test';
//                $username = 'root';
//                $passwd = '123456';

                //配置解析
                $dsn = $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];
                $username = $config['username'];
                $passwd = $config['password'];

                //1、通过参数形式链接
                $this->links[$linkNum] = new PDO($dsn, $username, $passwd, $this->options);

                //2、通过uri方式链接

                //3、通过配置文件链接php.ini

            } catch (\PDOException $e) {
                if ($autoConnection) {
//                    Log::record($e->getMessage(), 'error');
                    return $this->connect($autoConnection, $linkNum);
                } else {
                    throw $e;
                }
            } catch(\Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        return $this->links[$linkNum];
    }

    /**
     * Note: 执行查询 返回数据集
     * User: Eleven
     * Date: 2018/9/20 1:37
     * @param               $sql SQL语句
     * @param array         $bind 参数绑定
     * @param bool|false    $master 是否在主服务器读操作
     * @return bool
     */
    public function query($sql, $bind = [], $master = false)
    {
        //初始化数据库链接，惰性链接，有SQL执行才链接
        $this->initConnect();

        //链接失败
        if(!$this->linkID)
        {
            return false;
        }
        //记录SQL语句，未绑定参数的
        $this->queryStr = $sql;
        //记录参数绑定
        if($bind)
        {
            $this->bind = $bind;
        }

        try {
            //调试开始
            $this->debug(true);

            //释放上一次的查询结果
            if(!empty($this->PDOStatement))
            {
                $this->free();
            }

            //预处理
            if(empty($this->PDOStatement))
            {
                $this->PDOStatement = $this->linkID->prepare($sql);
            }

            //参数绑定
            $this->bindValue($bind);

            //执行语句
            $this->PDOStatement->execute();

            //调试结束
            $this->debug(false,'',$master);

            //返回结果集
            $result = $this->PDOStatement->fetchAll($this->fetchType);
            $this->numRows = count($result);
            return $result;

        } catch (\PDOException $e) {}



    }

    /**
     * Note: 参数绑定
     * 支持 ['name'=>'value','id'=>123] 对应命名占位符
     * 或者 ['value',123] 对应问号占位符
     * User: Eleven
     * Date: 2018/9/20 21:32
     * @param array $bind
     */
    protected function bindValue(array $bind = [])
    {
        foreach($bind as $k => $v)
        {
            //
            $param = is_numeric($k) ? $k + 1 : ':' . $k;
            if(is_array($v)) {
                //绑定值类型
                if ($v[1] == PDO::PARAM_INT && $v[0] == '') {
                    $v[0] = 0;
                }
                $result = $this->PDOStatement->bindValue($param, $v[0], $v[1]);
            } else {
                $result = $this->PDOStatement->bindValue($param,$v);
            }
            if(!$result)
            {
                //绑定失败抛出异常
            }
        }
    }

    /**
     * Note: 释放查询结果
     * User: Eleven
     * Date: 2018/9/20 21:25
     */
    public function free()
    {
        $this->PDOStatement = null;
    }

    /**
     * Note: 数据库调试 记录当前SQL及分析性能
     * User: Eleven
     * Date: 2018/9/20 16:32
     * @param $mark
     * @param string $sql
     * @param bool|false $master
     */
    protected function debug($mark, $sql = '' ,$master = false)
    {
        //开启调试模式
        if($this->config['debug'])
        {
            //开始调试
            if($mark){
                //记录开始时间
//                Debug::remark('queryStartTime', 'time');
            } else {
                //记录结束时间
//                Debug::remark('queryEndTime', 'time');
                //获取时间差
//                $runtime = Debug::getRangeTime('queryStartTime', 'queryEndTime');
                //获得最后一次的SQL
                $sql     = $sql ?: $this->getLastsql();

                //SQL性能分析，针对查询语句
                $result = [];
                if($this->config['sql_explain'] && stripos(trim($sql), 'select') === 0)
                {
                    $result = $this->getExplain($sql);
                }

                // SQL监听
//                $this->trigger($sql, $runtime, $result, $master);
            }

        }

    }

    /**
     * Note: 获取最后一次执行的SQL
     * User: Eleven
     * Date: 2018/9/20 16:33
     * @return mixed
     */
    public function getLastSql()
    {
        return $this->getRealSql($this->queryStr, $this->bind);
    }

    /**
     * Note: 获取最近插入的ID
     * User: Eleven
     * Date: shijian
     * @param null $sequence    自增序列名
     * @return mixed
     */
    public function getLastInsID($sequence = null)
    {
        return $this->linkID->lastInsertId($sequence);
    }

    /**
     * Note: 根据参数绑定组装最终的SQL语句
     * User: Eleven
     * Date: 2018/9/20 16:36
     * @param $sql  带参数绑定的sql语句
     * @param array $bind 参数绑定列表
     * @return string
     */
    public function getRealSql($sql, array $bind = [])
    {

        //数组的需装成字符串
        if(is_array($sql))
        {
            $sql = implode(';',$sql);
        }

        //绑定参数
        foreach($bind as $k => $v){
            //绑定值
            $value = is_array($v) ? $v[0] : $v;
            //PDO::PARAM_STR->表示 SQL 中的 CHAR、VARCHAR 或其他字符串类型
            $type = is_array($v) ? $v[1] : PDO::PARAM_STR;

            if(PDO::PARAM_STR == $type) {//字符串过滤
                $value = $this->quote($value);
            } elseif(PDO::PARAM_INT == $type) {//数字过滤
                $value = (float) $value;
            }

            //判断占位符
            $sql = is_numeric($k) ?
                substr_replace($sql, $value, strpos($sql, '?'),1) :
                str_replace(
                    [':' . $k . ')', ':' . $k . ',', ':' . $k . ' ', ':' . $k . PHP_EOL],
                    [$value . ')', $value . ',', $value . ' ', $value . PHP_EOL],
                    $sql . ' ');
        }

        return trim($sql);
    }

    /**
     * Note: SQL指令安全过滤
     * User: Eleven
     * Date: 2018/9/20 16:49
     * @param string $str
     * @param bool|false $master 是否主库查询
     * @return string
     */
    public function quote($str = '', $master = false)
    {
        $this->initConnect($master);
        return $this->linkID ? $this->linkID->quote($str) : $str;
    }

    /**
     * Note: 代理调用Query类的查询方法
     * User: Eleven
     * Date: 2018/9/18 2:37
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getQuery(),$name],$arguments);
    }

    /**
     * Note: SQL参数绑定---测试
     * User: Eleven
     * Date: 2018/9/18 14:19
     * @param $sql
     * @param string $bind
     * @return mixed|string
     */
    protected function bindParam($sql, $bind = '')
    {
        $argc = func_get_args();
        $prepareSql = array_shift($argc);
        $bind = array_shift($argc);
        $i = 0;
        while($i < strlen($bind))
        {
            switch($i)
            {
                case 's':
                    $argc[$i] = "'" . addcslashes($argc[$i]) . "'";
                    break;
                case 'i':
                    $argc[$i] = intval($argc[$i]);
                    break;
                default:
                    break;
            }

            $prepareSql = str_repeat('?',$argc[$i],$prepareSql);
            $i ++;
        }

        return $prepareSql;
    }

    /**
     * Note: getExplain
     * User: Eleven
     * Date: 2018/9/20 21:19
     * @param $sql
     * @return mixed
     */
    abstract protected function getExplain($sql);
}