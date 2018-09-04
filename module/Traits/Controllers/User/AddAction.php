<?php

namespace Traits\Controllers\User;

use User\Form\UserForm;
use User\Model\User;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds User.
     *
     * On a get request, addAction() will display
     * a form for adding a new User.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('user');

        $form = new UserForm('user');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $user = new User();

            $form->setInputFilter($user->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();
                User::sanitizeGuarded($data);
                $user->exchangeArray($data);
                $table->saveUser($user);

                return $this->redirect()->toRoute('user', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
