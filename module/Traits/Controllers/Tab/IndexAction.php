<?php

namespace Traits\Controllers\Tab;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Tab
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('tab');

        return new ViewModel([
            'tabs' => $table->fetchAll(),
        ]);
    }
}
