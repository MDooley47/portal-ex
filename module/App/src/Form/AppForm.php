<?php

namespace App\Form;

use Zend\Form\Form;
use Zend\Form\Element\File;

class AppForm extends Form
{
    public function __construct($name = "app", $options = [])
    {
        // We will ignore the name provided to the constructor
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
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);
        $this->add([
            'name' => 'url',
            'type' => 'url',
            'options' => [
                'label' => 'URL',
            ],
        ]);
        /*$this->add([
            'name' => 'iconPath',
            'type' => 'file',
            'validators' => [
                [
                    'Size',
                    false,
                    102400
                ],
                [
                    'Extension',
                    false,
                    'jpg,png,gif'
                ]
            ],
            'destination' => realpath(APPLICATION_PATH . '/data/storage/images/'),
            'options' => [
                'label' => 'Icon',
            ],
        ]);*/
        $file = new File('image-file');
        $file->setLabel('Icon');
        $file->setAttribute('id', 'iconPath');
        $file->setAttribute('name', 'iconPath');

        $this->add($file);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
