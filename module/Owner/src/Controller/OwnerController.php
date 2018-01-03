<?php

namespace Owner\Controller;

use Owner\Form\OwnerForm;
use Owner\Model\Owner;
use Owner\Model\OwnerTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class OwnerController extends AbstractActionController
{
    /**
     * OwnerTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs OwnerController.
     * Sets $this->table to the paramater.
     *
     * @param OwnerTable $table
     * @return void
     */
    public function __construct(OwnerTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Owner
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'owners' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds Owner
     *
     * On a get request, addAction() will display
     * a form for adding a new Owner.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new OwnerForm('owner');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $owner = new Owner();

            $form->setInputFilter($owner->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Owner::sanitizeGuarded($data);
                $owner->exchangeArray($data);
                $this->table->saveOwner($owner);

                return $this->redirect()->toRoute('owner', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Owner
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
              $owner = $this->table->getOwner($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('owner');
         }

        $form = new OwnerForm();
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
                Owner::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $owner->exchangeArray($data);
                $this->table->saveOwner($owner);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Owner
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

            $this->table->deleteOwner($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
