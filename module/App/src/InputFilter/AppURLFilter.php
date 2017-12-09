<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class AppURLFilter extends InputFilter
{

    public function init()
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
