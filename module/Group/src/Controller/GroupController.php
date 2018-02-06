<?php

namespace Group\Controller;

use Group\Form\GroupForm;
use Group\Model\Group;
use Group\Model\GroupTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class GroupController extends AbstractActionController
{
    /**
     * GroupTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs GroupController.
     * Sets $this->table to the paramater.
     *
     * @param GroupTable $table
     * @return void
     */
    public function __construct(GroupTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Group
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'groups' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds Group
     *
     * On a get request, addAction() will display
     * a form for adding a new Group.
     * On a post request, addAction() will validate
     * form data and add the group to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new GroupForm('group');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $group = new Group();

            $form->setInputFilter($group->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Group::sanitizeGuarded($data);
                $group->exchangeArray($data);
                $this->table->saveGroup($group);

                return $this->redirect()->toRoute('group', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Group
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
              $group = $this->table->getGroup($slug);
         }
         catch (Exception $ex)
         {
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
                Group::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $group->exchangeArray($data);
                $this->table->saveGroup($group);

                return $this->redirect()->toRoute('group');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Group
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

            $this->table->deleteGroup($slug);

            return $this->redirect()->toRoute('group');
        }

        // if it is not a post request, redirect to /group
        return $this->redirect()->toRoute('group');
    }
}
