<?php

namespace Traits\Controllers\App;

trait OpenAction
{
    /**
     * Opens App.
     *
     * Redirects to the url of the app.
     *
     * @return Redirect
     */
    public function OpenAction()
    {
        $table = $this->getTable('app');

        // get provided slug
        $slug = $this->params()->fromRoute('slug', 0);

        // redirect to /app if there was no slug provided.
        if (!$slug) {
            return $this->redirect()->toRoute('app');
        }

        // Try to get an app with the provided slug. If there is
        // no app redirect to /app
        try {
            $app = $table->getApp($slug);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('app');
        }

        // redirect to app.url
        return $this->redirect()->toUrl($app->url);
    }
}
