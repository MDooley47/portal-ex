<?php

namespace Traits\Controllers\Setting;

use Setting\Form\SettingForm;
use Setting\Model\Setting;
use Zend\View\Model\ViewModel;

trait EditAction
{
    /**
     * Edits Setting.
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('setting');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /setting/add if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('setting', ['action' => 'add']);
        }

        // Try to get an setting with the provided slug. If there is
        // no setting, redirect to /setting
        try {
            $setting = $table->getSetting($slug);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('setting');
        }

        $form = new SettingForm();
        $form->bind($setting);
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

            $form->setInputFilter($setting->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                Setting::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $setting->exchangeArray($data);
                $table->saveSetting($setting);

                return $this->redirect()->toRoute('setting');
            }
        }

        return $viewData;
    }
}
