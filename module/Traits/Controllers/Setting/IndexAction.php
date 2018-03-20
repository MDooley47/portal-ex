<?php

namespace Traits\Controllers\Setting;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Setting
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('setting');

        return new ViewModel([
            'settings' => $table->fetchAll(),
        ]);
    }
}
