<?php

namespace Traits\Controllers\App;

trait DeleteAction
{
    /**
     * Deletes App.
     *
     * Removes the app from the database
     * and removes the app's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('app');

        $request = $this->getRequest();

        // iff it is a post request, the app will be deleted
        // after delete redirect to /app
        if ($request->isPost()) {
            $slug = $request->getPost('slug');

            $table->deleteApp($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /
        return $this->redirect()->toRoute('home');
    }
}
