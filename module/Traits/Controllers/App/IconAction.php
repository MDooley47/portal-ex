<?php

namespace Traits\Controllers\App;

trait IconAction
{
    /**
     * Displays Icon
     *
     * Sends the App's Icon via XSendFile.
     *
     * @return Response|Redirect
     */
    public function iconAction()
    {
        $table = $this->getTable('app');

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
             $app = $table->getApp($slug);
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
}
