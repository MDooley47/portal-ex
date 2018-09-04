<?php

namespace Traits\Controllers\GroupType;

use GroupType\Form\GroupTypeForm;
use GroupType\Model\GroupType;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds GroupType.
     *
     * On a get request, addAction() will display
     * a form for adding a new GroupType.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('grouptype');

        $form = new GroupTypeForm('grouptype');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $grouptype = new GroupType();

            $form->setInputFilter($grouptype->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();
                GroupType::sanitizeGuarded($data);
                $grouptype->exchangeArray($data);
                $table->saveGroupType($grouptype);

                return $this->redirect()->toRoute('grouptype', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
