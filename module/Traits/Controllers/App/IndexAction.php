<?php

namespace Traits\Controllers\App;

trait IndexAction
{
    /**
     * Displays the index page for App
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $basePath = array_values(array_filter(
            explode('/', $this->getRequest()->getUri()->getPath())))[0];

        if ($basePath == 'app') $this->redirect()->toUrl('/');

        $table = $this->getTable('app');

        return new ViewModel([
            'apps' => $table->fetchAll(),
        ]);
    }
}
