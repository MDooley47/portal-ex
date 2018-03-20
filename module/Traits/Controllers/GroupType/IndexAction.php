<?php

namespace Traits\Controllers\GroupType;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for GroupType
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('grouptype');

        return new ViewModel([
            'grouptypes' => $table->fetchAll(),
        ]);
    }
}
