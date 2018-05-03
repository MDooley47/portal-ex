<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\HasTables;
use Traits\Controllers\User\AddAction;
use Traits\Controllers\User\DeleteAction;
use Traits\Controllers\User\EditAction;
use Traits\Controllers\User\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class UserController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
