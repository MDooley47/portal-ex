<?php

namespace Traits\Controllers\Tab;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Tab.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('tab');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', null);

        if ((!isset($slug)) || false) {
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel([
            'apps' => $table->getTab($slug)->getApps(),
        ]);
    }
}
