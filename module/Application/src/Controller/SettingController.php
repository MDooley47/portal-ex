<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\HasTables;
use Traits\Controllers\Setting\AddAction;
use Traits\Controllers\Setting\DeleteAction;
use Traits\Controllers\Setting\EditAction;
use Traits\Controllers\Setting\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class SettingController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
