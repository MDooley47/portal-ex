<?php

namespace Traits\Controllers\Attribute;

use Attribute\Form\AttributeForm;
use Attribute\Model\Attribute;

use Zend\View\Model\ViewModel;

trait AddAction
{
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
        $table = $this->getTable('attribute');

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
                $table->saveAttribute($attribute);

                return $this->redirect()->toRoute('attribute', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
