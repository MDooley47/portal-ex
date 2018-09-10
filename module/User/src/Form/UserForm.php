<?php

namespace User\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UserForm extends Form
{
    /**
     * Constructs UserForm.
     * * Calls the parent (Zend\Form\Form) constructor
     * * Calls $this->addElements.
     *
     * @param string     $name
     * @param dictionary $options
     *
     * @return void
     */
    public function __construct($name = 'user', $options = [])
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
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'name'    => 'email',
            'type'    => Element\Email::class,
            'options' => [
                'label' => 'Email',
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
