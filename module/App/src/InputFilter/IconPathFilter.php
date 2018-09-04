<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class IconPathFilter extends InputFilter
{
    /**
     * Constructs IconPathFilter.
     *
     * @return IconPathFilter $this
     */
    public function __construct()
    {
        $this->add([
            'name'       => 'iconPath',
            'required'   => true,
            'validators' => [
                [
                    'name'    => 'stringLength',
                    'options' => [
                        'min' => 22,
                    ],
                ],
            ],
        ]);

        return $this;
    }
}
