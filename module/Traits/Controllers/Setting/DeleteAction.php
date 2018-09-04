<?php

namespace Traits\Controllers\Setting;

trait DeleteAction
{
    /**
     * Deletes Setting.
     *
     * Removes the setting from the database
     * and removes the setting's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('setting');

        $request = $this->getRequest();

        // iff it is a post request, the setting will be deleted
        // after delete redirect to /setting
        if ($request->isPost()) {
            $slug = $request->getPost('slug');

            $table->deleteSetting($slug);

            return $this->redirect()->toRoute('setting');
        }

        // if it is not a post request, redirect to /setting
        return $this->redirect()->toRoute('setting');
    }
}
