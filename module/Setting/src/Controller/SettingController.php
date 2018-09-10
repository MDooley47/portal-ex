<?php

namespace Setting\Controller;

use Traits\Controllers\Setting\AddAction;
use Traits\Controllers\Setting\DeleteAction;
use Traits\Controllers\Setting\EditAction;
use Traits\Controllers\Setting\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class SettingController extends AbstractActionController
{
    use HasTables, AddAction, DeleteAction, EditAction,
        IndexAction;

    /**
     * SettingTable to be used to interface with the tableGateway/database.
     */
    private $table;

    /**
     * Constructs SettingController.
     * Sets $this->table to the paramater.
     *
     * @param SettingTable $table
     *
     * @return void
     */
    public function __construct(SettingTable $table)
    {
        $this->table = $table;
    }
}
