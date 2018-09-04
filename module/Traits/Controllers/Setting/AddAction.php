<?php

namespace Traits\Controllers\Setting;

use Setting\Form\SettingForm;
use Setting\Model\Setting;
use Zend\View\Model\ViewModel;

trait AddAction
{
    /**
     * Adds Setting.
     *
     * On a get request, addAction() will display
     * a form for adding a new Setting.
     * On a post request, addAction() will validate
     * form data and add the setting to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('setting');

        $form = new SettingForm('setting');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $setting = new Setting();

            $form->setInputFilter($setting->getInputFilter());
            if ($form->isValid()) {
                $data = $form->getData();
                Setting::sanitizeGuarded($data);
                $setting->exchangeArray($data);
                $table->saveSetting($setting);

                return $this->redirect()->toRoute('setting', ['action' => 'add']);
            }
        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }
}
