<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class AppNameFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 3
                    ],
                ],
            ],
        ]);

        return $this;
    }
}

?>
