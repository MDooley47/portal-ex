<?php

namespace Model\Concerns;

use Zend\Db\Sql\Sql;

trait QueryBuilder
{
    protected static $query;

    public static function where($condition)
    {
        self::$query = $query = new Sql(databaseAdapter());
        $select = $query->select(self::$table);
        $select->where($condition);
    }
}
