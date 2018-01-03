<?php

namespace Privilege\Controller;

use Privilege\Form\PrivilegeForm;
use Privilege\Model\Privilege;
use Privilege\Model\PrivilegeTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class PrivilegeController extends AbstractActionController
{
    /**
     * PrivilegeTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs PrivilegeController.
     * Sets $this->table to the paramater.
     *
     * @param PrivilegeTable $table
     * @return void
     */
    public function __construct(PrivilegeTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Privilege
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'privilege' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds Privilege
     *
     * On a get request, addAction() will display
     * a form for adding a new Privilege.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new PrivilegeForm('owner');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $owner = new Privilege();

            $form->setInputFilter($owner->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Privilege::sanitizeGuarded($data);
                $owner->exchangeArray($data);
                $this->table->savePrivilege($owner);

                return $this->redirect()->toRoute('owner', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Privilege
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
            return $this->redirect()->toRoute('owner', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $owner = $this->table->getPrivilege($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('owner');
         }

        $form = new PrivilegeForm();
        $form->bind($owner);
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

            $form->setInputFilter($owner->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                Privilege::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $owner->exchangeArray($data);
                $this->table->savePrivilege($owner);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Privilege
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

            $this->table->deletePrivilege($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
