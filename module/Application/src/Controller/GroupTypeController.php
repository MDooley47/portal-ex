<?php

namespace Application\Controller;

use Traits\Controllers\GroupType\AddAction;
use Traits\Controllers\GroupType\DeleteAction;
use Traits\Controllers\GroupType\EditAction;
use Traits\Controllers\GroupType\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class GroupTypeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
