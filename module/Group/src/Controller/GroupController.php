<?php

namespace Group\Controller;

use Traits\HasTables;
use Traits\Controllers\Group\AddAction;
use Traits\Controllers\Group\DeleteAction;
use Traits\Controllers\Group\EditAction;
use Traits\Controllers\Group\IndexAction;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class GroupController extends AbstractActionController
{
    /**
     * GroupTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs GroupController.
     * Sets $this->table to the paramater.
     *
     * @param GroupTable $table
     * @return void
     */
    public function __construct(GroupTable $table)
    {
        $this->table = $table;
    }
}
