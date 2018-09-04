<?php

namespace Application\Controller;

use Traits\Controllers\Privilege\AddAction;
use Traits\Controllers\Privilege\DeleteAction;
use Traits\Controllers\Privilege\EditAction;
use Traits\Controllers\Privilege\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class PrivilegeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
