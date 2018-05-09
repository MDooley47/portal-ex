<?php

namespace Traits\Controllers\Group;

trait DeleteAction
{
    /**
     * Deletes Group
     *
     * Removes the group from the database
     * and removes the group's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('group');

        $request = $this->getRequest();

        // iff it is a post request, the group will be deleted
        // after delete redirect to /group
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteGroup($slug);

            return $this->redirect()->toRoute('group');
        }

        // if it is not a post request, redirect to /group
        return $this->redirect()->toRoute('group');
    }
}
