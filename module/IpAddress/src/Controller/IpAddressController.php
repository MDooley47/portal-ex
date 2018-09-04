<?php

namespace IpAddress\Controller;

use Traits\Controllers\IpAddress\AddAction;
use Traits\Controllers\IpAddress\DeleteAction;
use Traits\Controllers\IpAddress\EditAction;
use Traits\Controllers\IpAddress\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class IpAddressController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    /**
     * IpAddressTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs IpAddressController.
     * Sets $this->table to the paramater.
     *
     * @param IpAddressTable $table
     *
     * @return void
     */
    public function __construct(IpAddressTable $table)
    {
        $this->table = $table;
    }
}
