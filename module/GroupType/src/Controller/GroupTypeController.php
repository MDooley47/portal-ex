<?php

namespace GroupType\Controller;

use Traits\Controllers\GroupType\AddAction;
use Traits\Controllers\GroupType\DeleteAction;
use Traits\Controllers\GroupType\EditAction;
use Traits\Controllers\GroupType\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class GroupTypeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;
    /**
     * GroupTypeTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs GroupTypeController.
     * Sets $this->table to the paramater.
     *
     * @param GroupTypeTable $table
     *
     * @return void
     */
    public function __construct(GroupTypeTable $table)
    {
        $this->table = $table;
    }
}
