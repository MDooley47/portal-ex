<?php

namespace Traits\Controllers\Tab;

use Tab\Form\TabForm;
use Tab\Model\Tab;

use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits Tab
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('tab');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /tab/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('tab', ['action' => 'add']);
        }

        // Try to get an tab with the provided slug. If there is
        // no tab, redirect to /tab
        try
        {
              $tab = $table->getTab($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('tab');
         }

        $form = new TabForm();
        $form->bind($tab);
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

            $form->setInputFilter($tab->getInputFilter());
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();
                Tab::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $tab->exchangeArray($data);
                $table->saveTab($tab);

                return $this->redirect()->toRoute('tab');
            }
        }

        return $viewData;
    }
}
