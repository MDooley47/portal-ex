<?php

namespace Traits\Controllers\Privilege;

use Privilege\Form\PrivilegeForm;
use Privilege\Model\Privilege;

use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds Privilege
     *
     * On a get request, addAction() will display
     * a form for adding a new Privilege.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('privilege');

        $form = new PrivilegeForm('privilege');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $privilege = new Privilege();

            $form->setInputFilter($privilege->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Privilege::sanitizeGuarded($data);
                $privilege->exchangeArray($data);
                $table->savePrivilege($privilege);

                return $this->redirect()->toRoute('privilege', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
