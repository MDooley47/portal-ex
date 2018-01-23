<?php

namespace Attribute\InputFilter;

use Zend\InputFilter\InputFilter;

class NameFilter extends InputFilter
{
    /**
     * Constructs NameFilter
     *
     * @return NameFilter $this
     */
    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1
                    ],
                ],
            ],
        ]);

        return $this;
    }
}

?>
