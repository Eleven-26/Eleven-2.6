<?php
/**
 * 数据库查询构造器
 * Created by PhpStorm.
 * User: chenkangfu
 * Date: 2018/9/8
 * Time: 22:23
 */

namespace fuji\database;

use fuji\database;
use fuji\Db;

/**
 * Class Query
 * @package fuji\database
 */
class Query
{

    //数据库Connection对象实例
    protected $connection;
    //数据库Builder对象实例
    protected $builder;
    //当前模型类名称
    protected $model;
    //查询参数
    protected $options = [];

    /**
     * Query constructor.
     * @param Connection|null $connection   数据库对象实例
     * @param null $model   模型对象
     */
    public function __construct(Connection $connection = null, $model = null)
    {
        $this->connection = $connection ?: Db::connect([], true);

        //设置
        $this->setBuilder();
    }

    /**
     * Note: 获取当前的数据库Connection对象
     * User: Eleven
     * Date: 2018/9/20 22:35
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Note: 指定当前操作的数据表
     * User: Eleven
     * Date: 2018/9/20 22:21
     * @param $table
     * @return $this
     */
    public function table($table)
    {
        $this->options['table'] = $table;
        return $this;
    }

    /**
     * Note: where 条件
     * ('id',1126) 或 ('id','=',1126) 或 ['id' => 1126]
     * User: Eleven
     * Date: 2018/9/21 0:06
     * @param $field    条件字段
     * @param null $op  条件表达式
     * @param null $condition   查询条件
     * @return $this
     */
    public function where($field, $op = null, $condition = null)
    {
        return $this;
    }

    /**
     * Note: 设置当前的数据库Builder对象
     * User: Eleven
     * Date: 2018/9/20 22:35
     */
    protected function setBuilder()
    {
        $class = $this->connection->getBuilder();
        $this->builder = new $class($this->connection, $this);
    }

}