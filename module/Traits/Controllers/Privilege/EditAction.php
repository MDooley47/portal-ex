<?php

namespace Traits\Controllers\Privilege;

use Privilege\Form\PrivilegeForm;
use Privilege\Model\Privilege;

use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits Privilege
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('privilege');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /privilege/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('privilege', ['action' => 'add']);
        }

        // Try to get an privilege with the provided slug. If there is
        // no privilege, redirect to /privilege
        try
        {
              $privilege = $table->getPrivilege($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('privilege');
         }

        $form = new PrivilegeForm();
        $form->bind($privilege);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = [
            'slug' => $slug,
            'form' => $form,
        ];

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($privilege->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                Privilege::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $privilege->exchangeArray($data);
                $table->savePrivilege($privilege);

                return $this->redirect()->toRoute('privilege');
            }
        }

        return $viewData;
    }
}
