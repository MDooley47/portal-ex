<?php

namespace Traits\Controllers\Tab;

trait DeleteAction
{
    /**
     * Deletes Tab
     *
     * Removes the tab from the database
     * and removes the tab's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('tab');

        $request = $this->getRequest();

        // iff it is a post request, the tab will be deleted
        // after delete redirect to /tab
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteTab($slug);

            return $this->redirect()->toRoute('tab');
        }

        // if it is not a post request, redirect to /tab
        return $this->redirect()->toRoute('tab');
    }
}
