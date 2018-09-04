<?php

namespace Traits\Controllers\OwnerType;

use OwnerType\Form\OwnerTypeForm;
use OwnerType\Model\OwnerType;
use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits OwnerType.
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('ownertype');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /ownertype/add if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('ownertype', ['action' => 'add']);
        }

        // Try to get an ownertype with the provided slug. If there is
        // no ownertype, redirect to /ownertype
        try {
            $ownertype = $table->getOwnerType($slug);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('ownertype');
        }

        $form = new OwnerTypeForm();
        $form->bind($ownertype);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = [
            'slug' => $slug,
            'form' => $form,
        ];

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($ownertype->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                OwnerType::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $ownertype->exchangeArray($data);
                $table->saveOwnerType($ownertype);

                return $this->redirect()->toRoute('ownertype');
            }
        }

        return $viewData;
    }
}
