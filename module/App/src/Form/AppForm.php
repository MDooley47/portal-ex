<?php

namespace App\Form;

use Zend\Form\Element;
use Zend\Form\Form;


class AppForm extends Form
{
    public function __construct($name = "app", $options = [])
    {
        parent::__construct($name, $options);

        $this->addElements();
    }

    private function addElements()
    {
        $this=>add([
            'name' => 'name';
            'options' => [
                'label' => 'App Name',
            ],
            'type' => 'text',
        ]);
    }
}
