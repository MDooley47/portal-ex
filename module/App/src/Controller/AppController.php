<?php

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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

    public function openAction()
    {

    }

    public function deleteAction()
    {

    }
}
