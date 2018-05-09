<?php

namespace Traits\Controllers\User;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for User
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('user');

        return new ViewModel([
            'users' => $table->fetchAll(),
            'tables_array' => $this->tables,
        ]);
    }
}
