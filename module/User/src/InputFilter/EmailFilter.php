<?php

namespace User\InputFilter;

use Zend\InputFilter\InputFilter;

class EmailFilter extends InputFilter
{
    /**
     * Constructs NameFilter.
     *
     * @return NameFilter $this
     */
    public function __construct()
    {
        $this->add([
            'name'       => 'email',
            'required'   => true,
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                    ],
                ],
            ],
        ]);

        return $this;
    }
}
