<?php

namespace Attribute\Controller;

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
    /**
     * AttributeTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs AttributeController.
     * Sets $this->table to the paramater.
     *
     * @param AttributeTable $table
     *
     * @return void
     */
    public function __construct(AttributeTable $table)
    {
        $this->table = $table;
    }
}
