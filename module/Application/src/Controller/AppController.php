<?php

namespace Application\Controller;

use Traits\Controllers\App\AddAction;
use Traits\Controllers\App\DeleteAction;
use Traits\Controllers\App\EditAction;
use Traits\Controllers\App\IconAction;
use Traits\Controllers\App\IndexAction;
use Traits\Controllers\App\OpenAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class AppController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IconAction, IndexAction, OpenAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
