<?php

namespace Group\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class GroupController extends AbstractActionController
{
    /**
     * GroupTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs GroupController.
     * Sets $this->table to the paramater.
     *
     * @param GroupTable $table
     *
     * @return void
     */
    public function __construct(GroupTable $table)
    {
        $this->table = $table;
    }
}
