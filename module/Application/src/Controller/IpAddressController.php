<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\HasTables;
use Traits\Controllers\IpAddress\AddAction;
use Traits\Controllers\IpAddress\DeleteAction;
use Traits\Controllers\IpAddress\EditAction;
use Traits\Controllers\IpAddress\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IpAddressController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
