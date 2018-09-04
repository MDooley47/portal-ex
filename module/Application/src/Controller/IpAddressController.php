<?php

namespace Application\Controller;

use Traits\Controllers\IpAddress\AddAction;
use Traits\Controllers\IpAddress\DeleteAction;
use Traits\Controllers\IpAddress\EditAction;
use Traits\Controllers\IpAddress\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class IpAddressController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
