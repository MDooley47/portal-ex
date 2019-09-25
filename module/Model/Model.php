<?php

namespace Model;

use Model\Concerns\HasAttributes;
use Model\Concerns\HasCast;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Tables\HasColumns;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

abstract class Model
{
    use HasAttributes, HasColumns, ExchangeArray, HasGuarded;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public static $primaryKey = 'id';
    protected static $table;
    public static $form;

    public function __construct(array $attributes = [])
    {
        self::protectedFill($attributes);
    }

    public function __toString()
    {
        return implode(' | ', $this->getArrayCopy());
    }

    /**
     * Get all rows.
     *
     * @return array
     */
    public static function all($limit = 50, $offset = 0)
    {
        $query = new Sql(databaseAdapter());
        $select = $query->select();
        $select->from(static::$table);
        if (isset($limit) && isset($offset)) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $statement = $query->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = (new ResultSet())->initialize($result);

        $models = [];

        foreach ($resultSet as $index => $row) {
            $models[$index] = static::cast($row->getArrayCopy());
        }

        return $models;
    }

    /**
     * Finds a model.
     *
     * @param $ids
     * @param null $primaryKey
     *
     * @return mixed
     */
    public static function find($ids, $primaryKey = null)
    {
        $primaryKey = $primaryKey ?? self::$primaryKey;

        $query = new Sql(databaseAdapter());

        $select = $query->select(self::$table);
        $select->where(self::buildMultiPKWhere($ids));
        $statement = $query->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $resultSet = (new ResultSet())->initialize($result);

        $models = [];

        foreach ($resultSet as $row) {
            $models[] = self::cast($row->getArrayCopy());
        }

        if (count($models) == 1) {
            $models = $models[0];
        }

        return $models;
    }

    /**
     * Saves model.
     *
     * @return $this
     */
    public function save()
    {
        $data = $this->getArrayCopy();
        unset($data[self::$primaryKey]);

        $query = new Sql(databaseAdapter());
        $update = $query->update(self::$table);
        $update->set($data);
        $update->where(self::buildPKWhere($this->{self::$primaryKey}));
        $statement = $query->prepareStatementForSqlObject($update);
        $statement->execute();

        return $this;
    }

    public function update($data)
    {
        $this->exchangeArray($data);

        $this->save();
    }

    public static function add(array $attributes)
    {
        return self::cast($attributes)->save();
    }

    abstract public function getArrayCopy();

    abstract static function cast(array $attributes);

    public static function buildMultiPKWhere($identifiers)
    {
        $where = '';
        if (!is_array($identifiers)) {
            $where .= self::buildPKWhere($identifiers);
        } else {
            $ids = array_shift($identifiers);
            $where .= self::buildPKWhere($ids);
            unset($ids);
            foreach ($identifiers as $ids) {
                $where .= ' OR '.self::buildPKWhere($ids);
            }
        }

        return $where;
    }

    public static function buildPKWhere($pk)
    {
        return self::$primaryKey."='".$pk."'";
    }
}
