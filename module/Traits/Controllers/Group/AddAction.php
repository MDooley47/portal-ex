<?php

namespace Traits\Controllers\Group;

use Group\Form\GroupForm;
use Group\Model\Group;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds Group.
     *
     * On a get request, addAction() will display
     * a form for adding a new Group.
     * On a post request, addAction() will validate
     * form data and add the group to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('group');

        $form = new GroupForm('group');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $group = new Group();

            $form->setInputFilter($group->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();

                Group::sanitizeGuarded($data);
                $group->exchangeArray($data);
                $table->saveGroup($group);

                return $this->redirect()->toRoute('group', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
