<?php

namespace App\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class App
{
    public $id;
    public $name;
    public $url;
    public $iconPath;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->iconPath = !empty($data['iconPath']) ? $data['iconPath'] : null;
    }
}
