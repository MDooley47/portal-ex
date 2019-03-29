<?php

namespace Model\Concerns;

trait QuickModelBoot
{
    public static function boot()
    {
        parent::$table = self::$table;
        parent::$primaryKey = self::$primaryKey ?? parent::$primaryKey;
    }
}
