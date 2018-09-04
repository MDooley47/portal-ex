<?php

namespace Application\Controller;

use Traits\Controllers\OwnerType\AddAction;
use Traits\Controllers\OwnerType\DeleteAction;
use Traits\Controllers\OwnerType\EditAction;
use Traits\Controllers\OwnerType\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class OwnerTypeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
