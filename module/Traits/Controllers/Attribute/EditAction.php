<?php

namespace Traits\Controllers\Attribute;

use Attribute\Form\AttributeForm;
use Attribute\Model\Attribute;
use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits Attribute.
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('attribute');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app/add if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('attribute', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try {
            $attribute = $table->getAttribute($slug);
        } catch (Exception $ex) {
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

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($attribute->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                Attribute::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $attribute->exchangeArray($data);
                $table->saveAttribute($attribute);

                return $this->redirect()->toRoute('attribute');
            }
        }

        return $viewData;
    }
}
