<?php

namespace Traits\Controllers\Group;

use Group\Form\GroupForm;
use Group\Model\Group;
use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits Group.
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('group');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /group/add if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('group', ['action' => 'add']);
        }

        // Try to get an group with the provided slug. If there is
        // no group, redirect to /group
        try {
            $group = $table->getGroup($slug);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('group');
        }

        $form = new GroupForm();
        $form->bind($group);
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

            $form->setInputFilter($group->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                Group::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $group->exchangeArray($data);
                $table->saveGroup($group);

                return $this->redirect()->toRoute('group');
            }
        }

        return $viewData;
    }
}
