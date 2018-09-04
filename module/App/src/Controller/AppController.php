<?php

namespace App\Controller;

use Traits\Controllers\App\AddAction;
use Traits\Controllers\App\DeleteAction;
use Traits\Controllers\App\EditAction;
use Traits\Controllers\App\IconAction;
use Traits\Controllers\App\IndexAction;
use Traits\Controllers\App\OpenAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class AppController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IconAction, IndexAction, OpenAction;
    /**
     * AppTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs AppController.
     * Sets $this->table to the paramater.
     *
     * @param AppTable $table
     *
     * @return void
     */
    public function __construct(AppTable $table)
    {
        $this->table = $table;
    }
}
