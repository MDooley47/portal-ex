<?php

namespace Setting\Form;

use Zend\Form\Form;

class SettingForm extends Form
{
    /**
     * Constructs SettingForm.
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
            'name'    => 'data',
            'type'    => 'text',
            'options' => [
                'label' => 'Data',
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
