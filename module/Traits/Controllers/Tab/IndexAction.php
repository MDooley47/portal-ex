<?php

namespace Traits\Controllers\Tab;

use SessionManager\Session;
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

        if (!$table->tabExists($slug)) {
            return $this->getResponse()->setStatusCode(404);
            // abort(404); // abort NOT_FOUND
        }

        Session::isActive();
        if (!$this
            ->getTable('userPrivileges')
            ->hasPrivilege(
                Session::getUser(),
                'admin',
                $this->getTable('ownerTabs')
                    ->getOwner($slug)
                    ->ownerSlug
            )) {
            return $this->getResponse()->setStatusCode(403);
            // abort(403); // abort FORBIDDEN
        }

        return new ViewModel([
            'apps' => $table->getTab($slug)->getApps(),
        ]);
    }
}
