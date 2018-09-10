<?php

namespace Application\Controller;

use Traits\Controllers\Tab\AddAction;
use Traits\Controllers\Tab\DeleteAction;
use Traits\Controllers\Tab\EditAction;
use Traits\Controllers\Tab\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class TabController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
