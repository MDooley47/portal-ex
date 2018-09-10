<?php

namespace Application\Controller;

use Traits\Controllers\Configuration\HelperFunctions;
use Traits\Controllers\Configuration\IndexAction;
use Traits\HasTables;
use Zend\Mvc\Controller\AbstractActionController;

class ConfigurationController extends AbstractActionController
{
    use HasTables, HelperFunctions, IndexAction;

    public function __construct($tables)
    {
        $this->addTableArray($tables);
    }
}
