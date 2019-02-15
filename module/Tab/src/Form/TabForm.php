<?php

namespace Tab\Form;

use Zend\Form\Form;

class TabForm extends Form
{
    /**
     * Constructs TabForm.
     * * Calls the parent (Zend\Form\Form) constructor
     * * Calls $this->addElements.
     *
     * @param string     $name
     * @param dictionary $options
     *
     * @return void
     */
    public function __construct($name = 'tab', $options = [])
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
            'name'    => 'group',
            'type'    => 'select',
            'options' => [
                'label'                     => 'GroupType',
                'disable_inarray_validator' => true,
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
