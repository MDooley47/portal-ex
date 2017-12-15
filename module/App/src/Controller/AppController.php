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
    private $table;

    /**
     * Constructs AppController
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
     * @return ViewModel
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

            $app = new App();

            $form->setInputFilter($app->getInputFilter(['hasIcon' => true]));
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();

                $data['iconPath'] = $data['icon']['tmp_name'];
                $form->setData($data);

                // Removes icon from the form to prevent Zend
                // from thinking there was an illegal file
                // upload.
                $form->remove('icon');
            }

            $form->setInputFilter($app->getInputFilter(['hasPath' => true]));
            if ($form->isValid())
            {
                $app->exchangeArray($form->getData());
                $this->table->saveApp($app);

                return $this->redirect()->toRoute('app', ['action' => 'add']);
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * Edits App
     *
     * @return Viewmodel
     */
    public function editAction()
    {
        // get provided id
        $id = (int) $this->params()->fromRoute('id', 0);

        // redirect to /app/add if there was no id provided.
        if (!$id) {
            return $this->redirect()->toRoute('app', ['action' => 'add']);
        }

        // Try to get an app with the provided id. If there is
        // no app, redirect to /app
        try {
              $app = $this->table->getApp($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('app');
         }

        $form = new AppForm();
        $form->bind($app);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setInputFilter($app->getInputFilter(['hasIcon' => true]));
            $form->setData($post);

            if ($form->isValid())
            {
                $data = $form->getData();

                $data['iconPath'] = $data['icon']['tmp_name'];
                $form->setData($data);

                // Removes icon from the form to prevent Zend
                // from thinking there was an illegal file
                // upload.
                $form->remove('icon');
            }

            $form->setInputFilter($app->getInputFilter(['hasPath' => true]));
            if ($form->isValid())
            {
                $app->exchangeArray($form->getData());
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
     * @return Response
     */
    public function iconAction()
    {
        // get provided id
        $id = (int) $this->params()->fromRoute('id', 0);

        // redirect to /app if there was no id provided.
        if (!$id) {
            return $this->redirect()->toRoute('app');
       }

       // Try to get an app with the provided id. If there is
       // no app, redirect to /app
       try {
             $app = $this->table->getApp($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('app');
        }

        // Check that there is a phone at app.iconPath. If
        // there is no file, redirect to /app
        if (!file_exists($app->iconPath)) {
            return $this->redirect()->toRoute('app');
        }

        // return a response with using X-Sendfile to
        // send the file to the user.
        $response = $this->getResponse();

        $response
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'image/' . pathinfo($app->iconPath, PATHINFO_EXTENSION))
            ->addHeaderLine('Content-Disposition', 'inline; filename="' . pathinfo($app->iconPath, PATHINFO_BASENAME) . '"')
            ->addHeaderLine("X-Sendfile", $app->iconPath);

        return $response;
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
        // get provided id
        $id = (int) $this->params()->fromRoute('id', 0);

        // redirect to /app if there was no id provided.
        if (!$id) {
            return $this->redirect()->toRoute('app');
       }

       // Try to get an app with the provided id. If there is
       // no app redirect to /app
       try {
             $app = $this->table->getApp($id);
        }
        catch (\Exception $ex) {
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

        // if it is a post request, the app will be deleted
        // after delete redirect to /app
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');

            $this->table->deleteApp($id);

            return $this->redirect()->toRoute('app');
        }

        // if it is not a post request, redirect to /app
        return $this->redirect()->toRoute('app');
    }
}
