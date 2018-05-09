<?php

namespace Traits\Controllers\GroupType;

use GroupType\Form\GroupTypeForm;
use GroupType\Model\GroupType;

use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits GroupType
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('grouptype');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /grouptype/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('grouptype', ['action' => 'add']);
        }

        // Try to get an grouptype with the provided slug. If there is
        // no grouptype, redirect to /grouptype
        try
        {
              $grouptype = $table->getGroupType($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('grouptype');
         }

        $form = new GroupTypeForm();
        $form->bind($grouptype);
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

            $form->setInputFilter($grouptype->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                GroupType::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $grouptype->exchangeArray($data);
                $table->saveGroupType($grouptype);

                return $this->redirect()->toRoute('grouptype');
            }
        }

        return $viewData;
    }
}
