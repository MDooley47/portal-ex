<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\HasTables;
use Traits\Controllers\Tab\AddAction;
use Traits\Controllers\Tab\DeleteAction;
use Traits\Controllers\Tab\EditAction;
use Traits\Controllers\Tab\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class TabController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
