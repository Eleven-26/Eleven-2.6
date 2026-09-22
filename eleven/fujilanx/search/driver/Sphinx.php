<?php
/**
 * Created by PhpStorm.
 * Note: Sphinx 全文搜索引擎
 * User: chenkangfu
 * Date: 2018/10/6
 * Time: 21:22
 */

namespace fuji\search\driver;

use fuji\Config;
use lib\sphinx\SphinxClient;

/**
 * Class Sphinx
 * @package fuji\search\driver
 */
class Sphinx
{
    //获取Sphinx类实例
    private static $sphinxObj;
    //Sphinx 连接
    private static $link;
    //配置信息
    private static $config;
    //当前索引
    private static $indexes;
    //SQL 条件组合
    private static $sql = [];
    //组装好的SQL
    private static $queryStr;

    /**
     * Sphinx constructor.
     * @param string $indexes
     * @param array $config
     */
    public function __construct($indexes = '', $config = [])
    {
        //获取配置
        if (empty($config)) {
            $config = Config::getConfig('sphinx');
        }
        self::$config = $config;

        //索引
        self::$indexes = $indexes;

        //Sphinx类实例
        self::$sphinxObj = new SphinxClient();
    }

    /**
     * Note: 获取Sphinx连接
     * User: Eleven
     * Date: 2018/10/6 21:47
     */
    public function connect()
    {
        //获取Sphinx连接
        if (empty(self::$link)) {
            self::$link = self::$sphinxObj->SetServer(self::$config['host'], self::$config['port']);
        }

        return self::$link;
    }

    /**
     * Note: 设置索引表
     * User: Eleven
     * Date: 2018/10/6 21:49
     * @param $indexes
     * @return $this
     */
    public function setIndexes($indexes)
    {
        self::$sql['index'] = $indexes;

        return $this;
    }

    /**
     * Note: where 查询条件，注意:这里一定要主动加上where 关键词 不能出现这样的情况 where 1
     * User: Eleven
     * Date: 2018/10/6 21:52
     * @param $where
     * @return $this
     */
    public function where($where)
    {
        self::$sql['where'] = ' where' . $where;

        return $this;
    }

    /**
     * Note: limit 分页条件
     * User: Eleven
     * Date: 2018/10/6 21:56
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        self::$sql['limit'] = $limit;

        return $this;
    }

    /**
     * Note: order 排序条件
     * User: Eleven
     * Date: 2018/10/6 22:11
     * @param $order
     * @return $this
     */
    public function order($order)
    {
        self::$sql['order'] = $order;

        return $this;
    }

    /**
     * Note: group 分组查询条件
     * User: Eleven
     * Date: 2018/10/6 23:05
     * @param $group    分组条件
     * @param $withGroup    分组内排序
     * @return $this
     */
    public function group($group, $withGroup)
    {
        self::$sql['group'] = $group;
        if ($group) {
            self::$sql['withGroup'] = $withGroup;
        }

        return $this;
    }

    /**
     * Note: 查询的字段
     * User: Eleven
     * Date: 2018/10/6 22:00
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        self::$sql['field'] = $field;

        return $this;
    }

    /**
     * Note: 获取查询结果
     * User: Eleven
     * Date: 2018/10/7 21:45
     * @return array
     */
    public function select()
    {
        //where 条件
        if (isset(self::$sql['where'])) {
            $where = self::$sql['where'];
        } else {
            $where = '';
        }

        //order 排序
        if (isset(self::$sql['order'])) {
            $order = ' ORDER BY ' . self::$sql['order'];
        }

        //分组
        if (isset(self::$sql['group'])) {
            $group = ' GROUP BY ' . self::$sql['group'];
            //组内排序
            if (isset(self::$sql['withGroup'])) {
                $withGroup = 'WITHIN GROUP ORDER BY ' . self::$sql['withGroup'];
            }
        }

        //limit 条件
        if (isset(self::$sql['limit'])) {
            $limit = 'limit ' . self::$sql['limit'];
        }

        //查询的字段
        if (isset(self::$sql['field'])) {
            $field = self::$sql['field'];
        } else {
            $field = '*';
        }

        //组装SQL
        self::$queryStr = sprintf("SELECT %S FROM %S %S %S %S %S", $field, self::$sql['index'], $where, $group, $order, $limit);
        $res = $this->query();
        $data = [];
        if ($res) {
            while ($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
                $data[] = $row;
            }
            $data['mate'] = $this->getMeta();
        }

        return $data;
    }

    /**
     * Note: 添加索引，注意，这里的添加并未考虑并发操作，可能在sphinx端会出现id冲突
     * User: Eleven
     * Date: 2018/10/7 22:56
     * @param $data
     * @param int $lastId
     * @return mixed
     * @throws \Exception
     */
    public function insert($data, $lastId = 0)
    {
        if (empty($data)) {
            //抛出异常
            throw new \Exception('data is not empty');
        }

        if ($lastId === 0) {
            $lastId = $this->getLastId();
        }

        $fields = $values = '';
        foreach ($data as $k => $v) {
            $fields .= ',' . $k;
            $values .= ",'" . $v . "'";
        }
        self::$queryStr = "INSERT INTO " . self::$sql['index'] ."(id" . $fields . ") values ($lastId {$values})";

        return $this->query();
    }

    /**
     * Note: 获取当前最大值id，实现如mysql的auto_increment功能
     * User: Eleven
     * Date: 2018/10/7 23:04
     * @return int
     */
    private function getLastId()
    {
        self::$queryStr = "SELECT * FROM " . self::$sql['index'] . " ORDER BY id DESC LIMIT 1";
        $res = $this->query();

        //存在值，取最大的id并 + 1，否则取1
        $row = mysql_fetch_array($res,MYSQL_ASSOC);
        if ($row) {
            $lastId = $row['id'] + 1;
        }

        return $lastId ?? 1;
    }
}
