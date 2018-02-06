<?php

namespace Traits\Controllers\App;

use App\Model\App;
use App\Form\AppForm;
use Zend\View\Model\ViewModel;


trait AddAction
{
    /**
     * Adds App
     *
     * On a get request, addAction() will display
     * a form for adding a new App.
     * On a post request, addAction() will validate
     * form data and add the app to do the database.
     *
     * @return ViewModel|Redirect
     */
    public function addAction()
    {
        $table = $this->getTable('app');

        $form = new AppForm('app');

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            $app = new App();

            // Checks if the form data is valid with the icon (image file)
            $form->setInputFilter($app->getInputFilter(['hasIcon' => true]));
            if ($form->isValid())
            {
                $data = $form->getData();

                $data['iconPath'] = removeBasePath($data['icon']['tmp_name']);
                $form->setData($data);

                // Removes icon from the form to prevent Zend
                // from thinking there was an illegal file
                // upload.
                $form->remove('icon');
            }

            // Checks if the form data is valid with the iconPath
            // (path to image file)
            $form->setInputFilter($app->getInputFilter(['hasPath' => true]));
            if ($form->isValid())
            {
                $data = $form->getData();
                App::sanitizeGuarded($data);
                $app->exchangeArray($data);
                $table->saveApp($app);

                return $this->redirect()->toRoute('app', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return (new ViewModel([
            'form' => $form,
        ]));
    }
}
