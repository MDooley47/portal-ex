<?php

namespace Traits\Controllers\App;

use SessionManager\Session;

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
        Session::start();
        $attributes = Session::get('attributes');

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

        // check/do attribute substitutions
        $finalUrl = $this->urlAttributeSub($app->url, $attributes);

        // redirect to app
        return $this->redirect()->toUrl($finalUrl);
    }

    private function urlAttributeSub ($origUrl, $attributes)
    {
      // look for attribute substitutions in url
      $lastPos = 0;
      $brace = '{';
      while (($position = strpos($origUrl, $brace, $lastPos)) !== false) {
          $lbpos[] = $position;
          $lastPos = $position + 1;
      }

      if ($lastPos > 0)
      {
        // left braces were found, continue substitutions
        $lastPos = 0;
        $brace = '}';
        while (($position = strpos($origUrl, $brace, $lastPos)) !== false) {
            $rbpos[] = $position;
            $lastPos = $position + 1;
        }

        // left and right braces must match
        if (count($lbpos) == count($rbpos))
        {
          // same number of left and right braces
          $finalUrl = '';

          $attrNumber = 0;
          $lastPos = 0;
          foreach ($lbpos as $lb)
          {
            // in each set of braces, left must be before right
            if ($lb < ($rbpos[$attrNumber] - 1))
            {
              // add from original URL up to the left brace
              $finalUrl .= substr($origUrl, $lastPos, $lb-$lastPos);
              $lastPos = $lb + 1;

              // read the attribute name
              $attrName = substr($origUrl, $lastPos, $rbpos[$attrNumber]-$lastPos);

              // substitute the attribute value
              $finalUrl .= $attributes[$attrName][0];
              $lastPos = $rbpos[$attrNumber] + 1;
              $attrNumber++;
            }
            else
            {
              return ($origUrl);
            }
          }
          // add last part of original URL
          $finalUrl .= substr($origUrl, $lastPos);

        }
        else
        {
          return ($origUrl);
        }

      }
      else
      {
        return ($origUrl);
      }

      return ($finalUrl);

    }
}
