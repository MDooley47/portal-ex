<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\HasTables;
use Traits\Controllers\Privilege\AddAction;
use Traits\Controllers\Privilege\DeleteAction;
use Traits\Controllers\Privilege\EditAction;
use Traits\Controllers\Privilege\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class PrivilegeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
