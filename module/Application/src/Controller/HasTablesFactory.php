<?php

namespace Application\Controller;

use SessionManager\TableModels\UserPrivilegesTableGateway;
use SessionManager\TableModels\UserGroupsTableGateway;
use Traits\HasTables;

trait HasTablesFactory
{
    use HasTables;

    private function addTables($additionalTables = [])
    {
        $this->init($additionalTables);
    }
}
