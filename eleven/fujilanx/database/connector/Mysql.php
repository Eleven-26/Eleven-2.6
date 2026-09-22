<?php
/**
 * Created by PhpStorm.
 * Note：MySQL 数据库驱动
 * User: chenkangfu
 * Date: 2018/8/28
 * Time: 21:50
 */

namespace fuji\database\connector;


use fuji\database\Connection;

/**
 * Class Mysql
 * @package fuji\database\connector
 */
class Mysql extends Connection
{

    /**
     * Note: SQL性能分析
     * User: Eleven
     * Date: shijian
     * @param $sql
     * @return mixed|void
     */
    protected function getExplain($sql)
    {
        $pdo    = $this->linkID->query("EXPLAIN " . $sql);
    }

}
