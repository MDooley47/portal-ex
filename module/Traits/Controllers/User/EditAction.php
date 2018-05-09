<?php

namespace Traits\Controllers\User;

use User\Form\UserForm;
use User\Model\User;

use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits User
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('user');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /user/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }

        // Try to get an user with the provided slug. If there is
        // no user, redirect to /user
        try
        {
              $user = $table->getUser($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('user');
         }

        $form = new UserForm();
        $form->bind($user);
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

            $form->setInputFilter($user->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                User::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $user->exchangeArray($data);
                $table->saveUser($user);

                return $this->redirect()->toRoute('user');
            }
        }

        return $viewData;
    }
}
