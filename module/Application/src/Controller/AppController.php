<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\Controllers\App\AddAction;
use Traits\Controllers\App\DeleteAction;
use Traits\Controllers\App\EditAction;
use Traits\Controllers\App\IconAction;
use Traits\Controllers\App\IndexAction;
use Traits\Controllers\App\OpenAction;
use Traits\Controllers\HasTables;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class AppController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IconAction, IndexAction, OpenAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
