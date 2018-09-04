<?php

namespace Application\Controller;

use Traits\Controllers\Group\AddAction;
use Traits\Controllers\Group\DeleteAction;
use Traits\Controllers\Group\EditAction;
use Traits\Controllers\Group\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class GroupController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
