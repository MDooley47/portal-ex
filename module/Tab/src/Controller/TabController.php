<?php

namespace Tab\Controller;

use Traits\Controllers\Tab\AddAction;
use Traits\Controllers\Tab\DeleteAction;
use Traits\Controllers\Tab\EditAction;
use Traits\Controllers\Tab\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class TabController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    /**
     * TabTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs TabController.
     * Sets $this->table to the paramater.
     *
     * @param TabTable $table
     *
     * @return void
     */
    public function __construct(TabTable $table)
    {
        $this->table = $table;
    }
}
