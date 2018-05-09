<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\Controllers\Group\AddAction;
use Traits\Controllers\Group\DeleteAction;
use Traits\Controllers\Group\EditAction;
use Traits\Controllers\Group\IndexAction;
use Traits\HasTables;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class GroupController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
