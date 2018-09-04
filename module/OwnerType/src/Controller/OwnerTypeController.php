<?php

namespace OwnerType\Controller;

use Traits\Controllers\OwnerType\AddAction;
use Traits\Controllers\OwnerType\DeleteAction;
use Traits\Controllers\OwnerType\EditAction;
use Traits\Controllers\OwnerType\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class OwnerTypeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;
    /**
     * OwnerTypeTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs OwnerTypeController.
     * Sets $this->table to the paramater.
     *
     * @param OwnerTypeTable $table
     *
     * @return void
     */
    public function __construct(OwnerTypeTable $table)
    {
        $this->table = $table;
    }
}
