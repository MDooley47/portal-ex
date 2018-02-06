<?php

namespace Setting\Controller;

use Setting\Form\SettingForm;
use Setting\Model\Setting;
use Setting\Model\SettingTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class SettingController extends AbstractActionController
{
    /**
     * SettingTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs SettingController.
     * Sets $this->table to the paramater.
     *
     * @param SettingTable $table
     * @return void
     */
    public function __construct(SettingTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for Setting
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
     * Adds Setting
     *
     * On a get request, addAction() will display
     * a form for adding a new Setting.
     * On a post request, addAction() will validate
     * form data and add the group to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new SettingForm('group');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $group = new Setting();

            $form->setInputFilter($group->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                Setting::sanitizeGuarded($data);
                $group->exchangeArray($data);
                $this->table->saveSetting($group);

                return $this->redirect()->toRoute('group', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits Setting
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
              $group = $this->table->getSetting($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('group');
         }

        $form = new SettingForm();
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
                Setting::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $group->exchangeArray($data);
                $this->table->saveSetting($group);

                return $this->redirect()->toRoute('group');
            }
        }

        return $viewData;
    }

    /**
     * Deletes Setting
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

            $this->table->deleteSetting($slug);

            return $this->redirect()->toRoute('group');
        }

        // if it is not a post request, redirect to /group
        return $this->redirect()->toRoute('group');
    }
}
