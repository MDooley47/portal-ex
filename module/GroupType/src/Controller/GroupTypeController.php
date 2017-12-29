<?php

namespace GroupType\Controller;

use GroupType\Form\GroupTypeForm;
use GroupType\Model\GroupType;
use GroupType\Model\GroupTypeTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class GroupTypeController extends AbstractActionController
{
    /**
     * GroupTypeTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs GroupTypeController.
     * Sets $this->table to the paramater.
     *
     * @param GroupTypeTable $table
     * @return void
     */
    public function __construct(GroupTypeTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for GroupType
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'grouptypes' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds GroupType
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
        $form = new GroupTypeForm('grouptype');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $grouptype = new GroupType();

            $form->setInputFilter($grouptype->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                GroupType::sanitizeGuarded($data);
                $grouptype->exchangeArray($data);
                $this->table->saveGroupType($grouptype);

                return $this->redirect()->toRoute('grouptype', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits GroupType
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
            return $this->redirect()->toRoute('grouptype', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $grouptype = $this->table->getGroupType($slug);
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
                $this->table->saveGroupType($grouptype);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes GroupType
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

            $this->table->deleteGroupType($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
