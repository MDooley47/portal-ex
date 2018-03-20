<?php

namespace Traits\Controllers\OwnerType;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for OwnerType
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('ownertype');

        return new ViewModel([
            'ownerTypes' => $table->fetchAll(),
        ]);
    }
}
