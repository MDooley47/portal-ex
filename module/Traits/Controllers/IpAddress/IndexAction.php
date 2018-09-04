<?php

namespace Traits\Controllers\IpAddress;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for IpAddress.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $table = $this->getTable('ipaddress');

        return new ViewModel([
            'ipAddresses' => $table->fetchAll(),
        ]);
    }
}
