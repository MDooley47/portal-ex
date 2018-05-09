<?php

namespace Traits\Controllers\Privilege;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Privilege
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('privilege');

        return new ViewModel([
            'privileges' => $table->fetchAll(),
        ]);
    }
}
