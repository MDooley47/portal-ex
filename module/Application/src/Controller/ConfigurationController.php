<?php

namespace Application\Controller;

use SessionManager\Session;

use Traits\Controllers\Configuration\HelperFunctions;
use Traits\Controllers\Configuration\IndexAction;
use Traits\HasTables;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ConfigurationController extends AbstractActionController
{
    use HasTables, HelperFunctions, IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
