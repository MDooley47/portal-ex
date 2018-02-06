<?php

namespace User\Controller;

use User\Form\UserForm;
use User\Model\User;
use User\Model\UserTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    /**
     * UserTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs UserController.
     * Sets $this->table to the paramater.
     *
     * @param UserTable $table
     * @return void
     */
    public function __construct(UserTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for User
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'users' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds User
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
        $form = new UserForm('user');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $user = new User();

            $form->setInputFilter($user->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                User::sanitizeGuarded($data);
                $user->exchangeArray($data);
                $this->table->saveUser($user);

                return $this->redirect()->toRoute('user', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits User
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $user = $this->table->getUser($slug);
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
                $this->table->saveUser($user);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes User
     *
     * Removes the app from the database
     * and removes the app's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $request = $this->getRequest();

        // iff it is a post request, the app will be deleted
        // after delete redirect to /app
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $this->table->deleteUser($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
