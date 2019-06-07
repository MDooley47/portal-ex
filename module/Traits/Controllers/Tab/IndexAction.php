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
        if (!Session::isActive()) {
            // must be logged in
            return $this->redirect()->toRoute('login');
        }

        // get provided tab slug
        $slug = $this->params()->fromRoute('slug', null);
        if ((!isset($slug)) || false) {
            return $this->redirect()->toRoute('home');
        }

        if (!Session::hasTabAccess($slug)) {
            // if user isn't allowed on this tab, go to default
            return $this->redirect()->toRoute('home');
        }

        $table = $this->getTable('tab');

        if (!$table->tabExists($slug)) {
            return $this->redirect()->toRoute('home');
        }

        $user = Session::getUser();
        $this->layout()->setVariable('themeColor', $user->getThemeColor());
        $this->layout()->setVariable('logoFilename', $user->getLogoFilename());
        $this->layout()->setVariable('tabSlug', $slug);
        if (Session::hasPrivilege('sudo')) {
            $this->layout()->setVariable('sudo', true);
        }

        return new ViewModel([
          'apps' => $table->getTab($slug)->getApps(),
      ]);
    }
}
