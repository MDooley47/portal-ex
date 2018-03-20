<?php

namespace IpAddress\Controller;

use Traits\HasTables;
use Traits\Controllers\IpAddress\AddAction;
use Traits\Controllers\IpAddress\DeleteAction;
use Traits\Controllers\IpAddress\EditAction;
use Traits\Controllers\IpAddress\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class IpAddressController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    /**
     * IpAddressTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs IpAddressController.
     * Sets $this->table to the paramater.
     *
     * @param IpAddressTable $table
     * @return void
     */
    public function __construct(IpAddressTable $table)
    {
        $this->table = $table;
    }
}
