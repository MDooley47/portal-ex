<?php

namespace Attribute\Controller;

use Attribute\Form\AttributeForm;
use Attribute\Model\Attribute;
use Attribute\Model\AttributeTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class AttributeController extends AbstractActionController
{
    /**
     * AttributeTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs AttributeController.
     * Sets $this->table to the paramater.
     *
     * @param AttributeTable $table
     * @return void
     */
    public function __construct(AttributeTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Attribute
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'attributes' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds Attribute
     *
     * On a get request, addAction() will display
     * a form for adding a new Attribute.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new AttributeForm('attribute');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $attribute = new Attribute();

            $form->setInputFilter($attribute->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Attribute::sanitizeGuarded($data);
                $attribute->exchangeArray($data);
                $this->table->saveAttribute($attribute);

                return $this->redirect()->toRoute('attribute', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Attribute
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
            return $this->redirect()->toRoute('attribute', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $attribute = $this->table->getAttribute($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('attribute');
         }

        $form = new AttributeForm();
        $form->bind($attribute);
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

            $form->setInputFilter($attribute->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                Attribute::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $attribute->exchangeArray($data);
                $this->table->saveAttribute($attribute);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Attribute
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

            $this->table->deleteAttribute($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
