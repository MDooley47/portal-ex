<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\File\IsImage;

class AppIconFilter extends InputFilter
{

    public function init()
    {
        $this->add([
            'name' => 'icon',
            'type' => 'Zend\InputFilter\FileInput',
            'required' => true,
            'validators' => [

            ],
        ]);

        return $this;
    }
}

?>
