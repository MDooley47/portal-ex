<?php

namespace Traits\Controllers\App;

use App\Form\AppForm;
use App\Model\App;

trait EditAction
{
    /**
     * Edits App.
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        $table = $this->getTable('app');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app/add if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('app', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try {
            $app = $table->getApp($slug);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('app');
        }

        $form = new AppForm();
        $form->setData($app->getArrayCopy());
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = [
            'slug'    => $slug,
            'version' => $app->version,
            'form'    => $form,
        ];

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($app->getInputFilter(['hasIcon' => true]));
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $data['iconPath'] = removeBasePath($data['icon']['tmp_name']);
                $form->setData($data);

                // Removes icon from the form to prevent Zend
                // from thinking there was an illegal file
                // upload.
                $form->remove('icon');
            } elseif ($form->setInputFilter($app->getInputFilter())
                    && $form->isValid()) {
                $data = $form->getData();
                $data['iconPath'] = $app->iconPath;
                $form->setData($data);
            }

            $form->setInputFilter($app->getInputFilter(['hasPath' => true]));
            if ($form->isValid()) {
                $data = $form->getData();
                App::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $app->exchangeArray($data);
                $table->saveApp($app);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }
}
