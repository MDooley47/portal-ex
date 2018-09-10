<?php

namespace Traits\Controllers\Tab;

use Tab\Form\TabForm;
use Tab\Model\Tab;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds Tab.
     *
     * On a get request, addAction() will display
     * a form for adding a new Tab.
     * On a post request, addAction() will validate
     * form data and add the tab to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('tab');

        $form = new TabForm('tab');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $tab = new Tab();

            $form->setInputFilter($tab->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();
                Tab::sanitizeGuarded($data);
                $tab->exchangeArray($data);
                $table->saveTab($tab);

                return $this->redirect()->toRoute('tab', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
