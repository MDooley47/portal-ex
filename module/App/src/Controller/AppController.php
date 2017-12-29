<?php

namespace App\Controller;

use App\Form\AppForm;
use App\Model\App;
use App\Model\AppTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;

class AppController extends AbstractActionController
{
    /**
     * AppTable to be used to interface with the tableGateway/database
     */
    private $table;

    /**
     * Constructs AppController.
     * Sets $this->table to the paramater.
     *
     * @param AppTable $table
     * @return void
     */
    public function __construct(AppTable $table)
    {
        $this->table = $table;
    }

    /**
     * Displays the index page for App
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'apps' => $this->table->fetchAll(),
        ]);
    }

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
                $this->table->saveApp($app);

                return $this->redirect()->toRoute('app', ['action' => 'add']);
            }

        }

        // if not post request, return with viewModel
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits App
     *
     * @return ViewModel|Redirect
     */
    public function editAction()
    {
        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app/add if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('app', ['action' => 'add']);
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
              $app = $this->table->getApp($slug);
         }
         catch (Exception $ex)
         {
             return $this->redirect()->toRoute('app');
         }

        $form = new AppForm();
        $form->setData($app->getArrayCopy());
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = [
            'slug' => $slug,
            'version' => $app->version,
            'form' => $form,
        ];

        if ($request->isPost())
        {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($app->getInputFilter(['hasIcon' => true]));
            $form->setData($post);

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
            else if ($form->setInputFilter($app->getInputFilter())
                    && $form->isValid())
            {
                $data = $form->getData();
                $data['iconPath'] = $app->iconPath;
                $form->setData($data);
            }

            $form->setInputFilter($app->getInputFilter(['hasPath' => true]));
            if ($form->isValid())
            {
                $data = $form->getData();
                App::sanitizeGuarded($data);
                $data['slug'] = $slug;
                $app->exchangeArray($data);
                $this->table->saveApp($app);

                return $this->redirect()->toRoute('app');
            }
        }

        return $viewData;
    }

    /**
     * Displays Icon
     *
     * Sends the App's Icon via XSendFile.
     *
     * @return Response|Redirect
     */
    public function iconAction()
    {
        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('app');
        }

        // Try to get an app with the provided slug. If there is
        // no app, redirect to /app
        try
        {
             $app = $this->table->getApp($slug);
        }
        catch (Exception $ex)
        {
            return $this->redirect()->toRoute('app');
        }

        // Check that there is a phone at app.iconPath. If
        // there is no file, redirect to /app
        if (!file_exists(addBasePath($app->iconPath)))
        {
            return $this->redirect()->toRoute('app');
        }

        // return a response with using X-Sendfile to
        // send the file to the user.
        return ($this->getResponse())
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'image/'
                . pathinfo($app->iconPath, PATHINFO_EXTENSION))
            ->addHeaderLine('Content-Disposition', 'inline; filename="'
                . $app->slug . "."
                . pathinfo($app->iconPath, PATHINFO_EXTENSION) . '"')
            ->addHeaderLine("X-Sendfile", addBasePath($app->iconPath));
    }

    /**
     * Opens App
     *
     * Redirects to the url of the app.
     *
     * @return Redirect
     */
    public function openAction()
    {
        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app if there was no slug provided.
        if (! $slug)
        {
            return $this->redirect()->toRoute('app');
        }

        // Try to get an app with the provided slug. If there is
        // no app redirect to /app
        try
        {
             $app = $this->table->getApp($slug);
        }
        catch (Exception $ex)
        {
            return $this->redirect()->toRoute('app');
        }

        // redirect to app.url
        return $this->redirect()->toUrl($app->url);
    }

    /**
     * Deletes App
     *
     * Removes the app from the database
     * and removes the app's icon.
     *
     * @return Redirect
     */
    public function deleteAction()
    {
        $request = $this->getRequest();

        // iff it is a post request, the app will be deleted
        // after delete redirect to /app
        if ($request->isPost())
        {
            $slug = $request->getPost('slug');

            $this->table->deleteApp($slug);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
