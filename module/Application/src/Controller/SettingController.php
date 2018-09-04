<?php

namespace Application\Controller;

use Traits\Controllers\Setting\AddAction;
use Traits\Controllers\Setting\DeleteAction;
use Traits\Controllers\Setting\EditAction;
use Traits\Controllers\Setting\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class SettingController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
