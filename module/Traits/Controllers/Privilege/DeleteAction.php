<?php

namespace Traits\Controllers\Privilege;

trait DeleteAction
{
    /**
     * Deletes Privilege.
     *
     * Removes the privilege from the database
     * and removes the privilege's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('privilege');

        $request = $this->getRequest();

        // iff it is a post request, the privilege will be deleted
        // after delete redirect to /privilege
        if ($request->isPost()) {
            $slug = $request->getPost('slug');

            $table->deletePrivilege($slug);

            return $this->redirect()->toRoute('privilege');
        }

        // if it is not a post request, redirect to /privilege
        return $this->redirect()->toRoute('privilege');
    }
}
