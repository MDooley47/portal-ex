<?php

namespace Traits\Controllers\GroupType;

trait DeleteAction
{
    /**
     * Deletes GroupType
     *
     * Removes the grouptype from the database
     * and removes the grouptype's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('grouptype');

        $request = $this->getRequest();

        // iff it is a post request, the grouptype will be deleted
        // after delete redirect to /grouptype
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteGroupType($slug);

            return $this->redirect()->toRoute('grouptype');
        }

        // if it is not a post request, redirect to /grouptype
        return $this->redirect()->toRoute('grouptype');
    }

}
