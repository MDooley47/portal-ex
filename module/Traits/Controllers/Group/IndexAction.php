<?php

namespace Traits\Controllers\Group;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Group
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('group');

        return new ViewModel([
            'groups' => $table->fetchAll(),
            'tables_array' => $this->tables,
        ]);
    }
}
