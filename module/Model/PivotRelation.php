<?php

namespace Model;

use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;

abstract class PivotRelation extends Model implements Bootable
{
    use Boot;

    public static $polymorphic;
    public static $relations = [];

    /**
     * PivotRelation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Returns the number of relations.
     *
     * @return int
     */
    public function numberOfRelations()
    {
        return count($this->relations);
    }

    /**
     * Return if there exists a polymorphic relation.
     *
     * @return bool
     */
    public static function isPolymorphic()
    {
        if (!isset(self::$polymorphic)) {
            self::determinePolymorphic();
        }

        return self::$polymorphic;
    }

    /**
     * Determine if model contains polymorphic data.
     *
     * @return bool
     */
    protected static function determinePolymorphic()
    {
        self::$polymorphic = false;

        foreach (self::$relations as $relation => $value) {
            if (is_array($value) && isset($value['type'])) {
                self::$polymorphic = true;
            }
        }

        return self::$polymorphic;
    }

    /**
     * Gets model.
     *
     * @param $identifiers
     * @param null $primaryKey Not needed exists because this is a child of the class Model
     *
     * @return Model
     */
    public function get($identifiers, $primaryKey = null)
    {
    }

    protected static function buildWhere($label, $value = null)
    {
        if (!isset($value)) {
            $value = $label['id'];
            $label = $label['name'];
        }

        return '"'.$label.'" = "'.$value.'"';
    }

    /**
     * Rare case where save is not needed on an
     * object that inherits from Model\Model.
     */
    public function save()
    {
    }
}
