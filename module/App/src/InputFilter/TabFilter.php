<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class TabFilter extends InputFilter
{
    /**
     * Constructs NameFilter
     *
     * @param bool $required
     *
     * @return TabFilter $this
     */
    public function __construct($required = false)
    {
        $this->add([
            'name' => 'tab',
            'required' => $required,
        ]);

        return $this;
    }
}

?>
