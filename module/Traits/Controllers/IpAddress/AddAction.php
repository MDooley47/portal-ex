<?php

namespace Traits\Controllers\IpAddress;

use IpAddress\Form\IpAddressForm;
use IpAddress\Model\IpAddress;

use Zend\View\Model\ViewModel;

trait AddAction
{
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
        $table = $this->getTable('ipaddress');

        $form = new IpAddressForm('IpAddress');

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
                $table->saveIpAddress($ipAddress);

                return $this->redirect()->toRoute('ipaddress', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
