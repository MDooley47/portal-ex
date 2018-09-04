<?php

namespace Configuration\Form;

use Zend\Form\Form;

class ConfigurationForm extends Form
{
    /**
     * Constructs ConfigurationForm.
     * * Calls the parent (Zend\Form\Form) constructor
     * * Calls $this->addElements.
     *
     * @param string     $name
     * @param dictionary $options
     *
     * @return void
     */
    public function __construct($name = 'configuration', $options = [])
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
            'name'    => 'description',
            'type'    => 'text',
            'options' => [
                'label' => 'Description',
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
