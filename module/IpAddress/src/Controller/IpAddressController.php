<?php

namespace IpAddress\Controller;

use IpAddress\Form\IpAddressForm;
use IpAddress\Model\IpAddress;
use IpAddress\Model\IpAddressTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class IpAddressController extends AbstractActionController
{
    /**
     * IpAddressTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs IpAddressController.
     * Sets $this->table to the paramater.
     *
     * @param IpAddressTable $table
     * @return void
     */
    public function __construct(IpAddressTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for IpAddress
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'ipAddresses' => $this->table->fetchAll(),
        ]);
    }

    /**
     * Adds IpAddress
     *
     * On a get request, addAction() will display
     * a form for adding a new IpAddress.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $form = new IpAddressForm('ipAddress');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $ipAddress = new IpAddress();

            $form->setInputFilter($ipAddress->getInputFilter());
            if ($form->isValid())
            {
                $data = $form->getData();
                IpAddress::sanitizeGuarded($data);
                $ipAddress->exchangeArray($data);
                $this->table->saveIpAddress($ipAddress);

                return $this->redirect()->toRoute('ipAddress', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits IpAddress
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
            return $this->redirect()->toRoute('ipAddress', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $ipAddress = $this->table->getIpAddress($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('ipAddress');
         }

        $form = new IpAddressForm();
        $form->bind($ipAddress);
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

            $form->setInputFilter($ipAddress->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                IpAddress::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $ipAddress->exchangeArray($data);
                $this->table->saveIpAddress($ipAddress);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Deletes IpAddress
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

            $this->table->deleteIpAddress($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
