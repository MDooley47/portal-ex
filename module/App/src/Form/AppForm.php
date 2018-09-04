<?php

namespace App\Form;

use Zend\Form\Form;

class AppForm extends Form
{
    /**
     * Constructs AppForm.
     * * Calls the parent (Zend\Form\Form) constructor
     * * Calls $this->addElements.
     *
     * @param string     $name
     * @param dictionary $options
     *
     * @return void
     */
    public function __construct($name = 'app', $options = [])
    {
        parent::__construct($name, $options);

        $this->addElements();
    }

    /**
     * Adds Elements to the form.
     *
     * @return void
     */
    private function addElements()
    {
        $this->add([
            'name' => 'slug',
            'type' => 'hidden',
        ]);

        $this->add([
            'name'    => 'name',
            'type'    => 'text',
            'options' => [
                'label' => 'App Name',
            ],
        ]);

        $this->add([
            'name'    => 'url',
            'type'    => 'url',
            'options' => [
                'label' => 'App URL',
            ],
        ]);

        $this->add([
            'name'    => 'icon',
            'type'    => 'file',
            'options' => [
                'label' => 'App Icon',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
               'value' => 'Submit',
            ],
        ]);
    }
}
