<?php

namespace Traits\Controllers\IpAddress;

use IpAddress\Form\IpAddressForm;
use IpAddress\Model\IpAddress;

use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits IpAddress
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('ipaddress');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('ipaddress', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $ipAddress = $table->getIpAddress($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('ipaddress');
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
                $table->saveIpAddress($ipAddress);

                return $this->redirect()->toRoute('ipaddress');
            }
        }

        return $viewData;
    }
}
