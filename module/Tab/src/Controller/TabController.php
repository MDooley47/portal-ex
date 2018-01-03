<?php

namespace Tab\Controller;

use Tab\Form\TabForm;
use Tab\Model\Tab;
use Tab\Model\TabTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class TabController extends AbstractActionController
{
    /**
     * TabTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs TabController.
     * Sets $this->table to the paramater.
     *
     * @param TabTable $table
     * @return void
     */
    public function __construct(TabTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Tab
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'tabs' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds Tab
     *
     * On a get request, addAction() will display
     * a form for adding a new Tab.
     * On a post request, addAction() will validate
     * form data and add the group to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new TabForm('group');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $group = new Tab();

            $form->setInputFilter($group->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Tab::sanitizeGuarded($data);
                $group->exchangeArray($data);
                $this->table->saveTab($group);

                return $this->redirect()->toRoute('group', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Tab
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /group/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('group', ['action' => 'add']);
        }

        // Try to get an group with the provided slug. If there is
        // no group, redirect to /group
        try
        {
              $group = $this->table->getTab($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('group');
         }

        $form = new TabForm();
        $form->bind($group);
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

            $form->setInputFilter($group->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                Tab::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $group->exchangeArray($data);
                $this->table->saveTab($group);

                return $this->redirect()->toRoute('group');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Tab
     *
     * Removes the group from the database
     * and removes the group's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $request = $this->getRequest();

        // iff it is a post request, the group will be deleted
        // after delete redirect to /group
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $this->table->deleteTab($slug);

            return $this->redirect()->toRoute('group');
        }

        // if it is not a post request, redirect to /group
        return $this->redirect()->toRoute('group');
    }
}
