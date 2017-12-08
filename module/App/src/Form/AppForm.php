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
        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'App Name',
            ],
            'type' => 'text',
        ]);
        $this->add([
            'name' => 'url',
            'options' => [
                'label' => 'App URL',
            ],
            'type' => 'url',
        ]);

        /*
        $icon = new Element\File('image-file');
        $icon->setLabel('App Icon');
        $icon->setAttribute('id', 'icon')
             ->setAttribute('name', 'icon');
        */

        $this->add(
            (new Element\File('image-file'))
                ->setLabel('App Icon')
                ->setAttribute('id', 'icon')
                ->setAttribute('name', 'icon')
        );
    }
}
