<?php

namespace Traits;

trait HasTables
{
    private $tables = [];

    public function addTableArray($tables)
    {
        foreach ($tables as $name => $table)
        {
            $this->addTable($name, $table);
        }
    }

    public function addTable($name, $table)
    {
        $this->tables[strtolower($name)] = $table;
    }

    public function getTable($table)
    {
        return (isset($this->tables[strtolower($table)]))
            ? $this->tables[strtolower($table)]
            : $this->table;
    }
}

?>
