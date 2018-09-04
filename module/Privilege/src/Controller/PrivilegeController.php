<?php

namespace Privilege\Controller;

use Traits\Controllers\Privilege\AddAction;
use Traits\Controllers\Privilege\DeleteAction;
use Traits\Controllers\Privilege\EditAction;
use Traits\Controllers\Privilege\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class PrivilegeController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    /**
     * PrivilegeTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs PrivilegeController.
     * Sets $this->table to the paramater.
     *
     * @param PrivilegeTable $table
     *
     * @return void
     */
    public function __construct(PrivilegeTable $table)
    {
        $this->table = $table;
    }
}
