<?php

namespace Application\Controller;

use Traits\HasTables;

trait HasTablesFactory
{
    use HasTables;

    private function addTables($additionalTables = [])
    {
        $this->init($additionalTables);
    }
}
