<?php

namespace Traits\Controllers\OwnerType;

use OwnerType\Form\OwnerTypeForm;
use OwnerType\Model\OwnerType;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds OwnerType.
     *
     * On a get request, addAction() will display
     * a form for adding a new OwnerType.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('ownertype');

        $form = new OwnerTypeForm('ownertype');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $ownertype = new OwnerType();

            $form->setInputFilter($ownertype->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();
                OwnerType::sanitizeGuarded($data);
                $ownertype->exchangeArray($data);
                $table->saveOwnerType($ownertype);

                return $this->redirect()->toRoute('ownertype', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
