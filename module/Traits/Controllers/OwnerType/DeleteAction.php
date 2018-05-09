<?php

namespace Traits\Controllers\OwnerType;

trait DeleteAction
{
    /**
     * Deletes OwnerType
     *
     * Removes the ownertype from the database
     * and removes the ownertype's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('ownertype');

        $request = $this->getRequest();

        // iff it is a post request, the ownertype will be deleted
        // after delete redirect to /ownertype
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteOwnerType($slug);

            return $this->redirect()->toRoute('ownertype');
        }

        // if it is not a post request, redirect to /ownertype
        return $this->redirect()->toRoute('ownertype');
    }

}
