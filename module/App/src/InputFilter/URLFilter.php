<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class URLFilter extends InputFilter
{
    /**
     * Constructs URLFilter
     *
     * @return URLFilter $this
     */
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
