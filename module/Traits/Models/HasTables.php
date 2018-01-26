<?php

namespace Traits\Models;

trait HasTables
{
    private $tables = [];

    private function addTableArray($tables)
    {
        foreach ($tables as $name => $table)
        {
            $this->addTable($name, $table);
        }
    }

    private function addTable($name, $table)
    {
        $this->tables[strtolower($name)] = $table;
    }

    private function getTable($table)
    {
        return $this->tables[strtolower($table)];
    }
}

?>
