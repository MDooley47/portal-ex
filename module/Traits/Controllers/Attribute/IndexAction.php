<?php

namespace Traits\Controllers\Attribute;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Attribute
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('attribute');

        return new ViewModel([
            'attributes' => $table->fetchAll(),
        ]);
    }
}
