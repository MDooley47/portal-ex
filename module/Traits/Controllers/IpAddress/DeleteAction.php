<?php

namespace Traits\Controllers\IpAddress;

trait DeleteAction
{
    /**
     * Deletes IpAddress
     *
     * Removes the ipaddress from the database
     * and removes the ipaddress's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $table = $this->getTable('ipaddress');

        $request = $this->getRequest();

        // iff it is a post request, the ipaddress will be deleted
        // after delete redirect to /ipaddress
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $table->deleteIpAddress($slug);

            return $this->redirect()->toRoute('ipaddress');
        }

        // if it is not a post request, redirect to /ipaddress
        return $this->redirect()->toRoute('ipaddress');
    }
}
