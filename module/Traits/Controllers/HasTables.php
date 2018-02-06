<?php

namespace Traits\Controllers;

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
        return (isset($this->tables[strtolower($table)]))
            ? $this->tables[strtolower($table)]
            : $this->table;
    }
}

?>
