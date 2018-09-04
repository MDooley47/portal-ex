<?php

namespace Application\Controller;

use Traits\Controllers\User\AddAction;
use Traits\Controllers\User\DeleteAction;
use Traits\Controllers\User\EditAction;
use Traits\Controllers\User\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
