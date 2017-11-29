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
        $form = new AppForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $app = new App();
        $form->setInputFilter($app->getInputFilter());
        $form->setData($this->getRequest()->getPost());

        $icon = $request->getFiles()->toArray();

        print_r($icon["iconPath"]);

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $app->exchangeArray($form->getData());
        $this->table->saveApp($app);
        return $this->redirect()->toRoute('app');
    }

    public function editAction()
    {

    }

    public function openAction()
    {

    }

    public function deleteAction()
    {

    }
}
