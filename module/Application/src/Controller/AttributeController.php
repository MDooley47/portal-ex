<?php

namespace Application\Controller;

use Traits\Controllers\Attribute\AddAction;
use Traits\Controllers\Attribute\DeleteAction;
use Traits\Controllers\Attribute\EditAction;
use Traits\Controllers\Attribute\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class AttributeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
