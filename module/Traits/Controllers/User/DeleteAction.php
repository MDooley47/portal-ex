<?php

namespace Traits\Controllers\User;

trait DeleteAction
{
    /**
     * Deletes User
     *
     * Removes the user from the database
     * and removes the user's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('user');

        $request = $this->getRequest();

        // iff it is a post request, the user will be deleted
        // after delete redirect to /user
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteUser($slug);

            return $this->redirect()->toRoute('user');
        }

        // if it is not a post request, redirect to /user
        return $this->redirect()->toRoute('user');
    }
}
