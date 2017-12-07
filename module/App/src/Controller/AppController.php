<?php

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use App\Model\App;
use App\Model\AppTable;
use App\Form\AppForm;


class AppController extends AbstractActionController
{
    private $table;

    public function __construct(AppTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'apps' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {

    }

    public function editAction()
    {

    }

    public function iconAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('app', array(
                'action' => 'index'
            ));
       }

       try {
             $app = $this->table->getApp($id);
         }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('app', array(
                'action' => 'index'
            ));
        }

        if (!file_exists($app->iconPath)) {
            return $this->redirect()->toRoute('app', array(
                'action' => 'index'
            ));
        }

        $response = $this->getResponse();

        $response
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'image/' . pathinfo($app->iconPath, PATHINFO_EXTENSION))
            ->addHeaderLine('Content-Disposition', 'inline; filename="' . pathinfo($app->iconPath, PATHINFO_BASENAME) . '"')
            ->addHeaderLine("X-Sendfile", $app->iconPath);

        return $response;
    }

    public function openAction()
    {

    }

    public function deleteAction()
    {

    }
}
