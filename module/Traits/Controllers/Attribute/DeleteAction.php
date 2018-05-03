<?php

namespace Traits\Controllers\Attribute;

trait DeleteAction
{
    /**
     * Deletes Attribute
     *
     * Removes the app from the database
     * and removes the app's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('attribute');

        $request = $this->getRequest();

        // iff it is a post request, the app will be deleted
        // after delete redirect to /app
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteAttribute($slug);

            return $this->redirect()->toRoute('attribute');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('attribute');
    }
}
