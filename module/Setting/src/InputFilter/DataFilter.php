<?php

namespace Setting\InputFilter;

use Zend\InputFilter\InputFilter;

class DataFilter extends InputFilter
{
    /**
     * Constructs NameFilter
     *
     * @return NameFilter $this
     */
    public function __construct()
    {
        $this->add([
            'name' => 'data',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 5
                    ],
                ],
            ],
        ]);

        return $this;
    }
}

?>
