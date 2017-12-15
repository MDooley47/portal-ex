<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class AppURLFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'url',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Uri',
                ],
            ],
        ]);

        return $this;
    }
}

?>
