<?php

namespace App\Model;

use App\InputFilter\AppNameFilter;
use App\InputFilter\AppURLFilter;
use App\InputFilter\AppIconFilter;
use App\InputFilter\AppIconPathFilter;

use DomainException;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class App
{
    public $id;
    public $name;
    public $url;
    public $iconPath;
    protected $inputFilter;

    /**
     * Sets App's values
     *
     * Takes in dictionary and set instance variables.
     *
     * @return App $this
     */
    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->iconPath = !empty($data['iconPath']) ? $data['iconPath'] : null;

        return $this;
    }

    /**
     * Gets App's input filter
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     * Adds/Removes the iconPath filter depending upon
     * the passed in boolean value $options['hasPath'].
     * Adds/Removes the icon filter depending upon
     * the passed in boolean value $options['hasIcon'].
     *
     * @param Array $options
     * @return App $this
     */
    public function getInputFilter($options = [])
    {
        // Uses tmpFilter Variable for if setInputFilter is ever allowed

        $tmpFilter = $this->inputFilter;

        if (! $this->inputFilter)
        {
            $tmpFilter = (new InputFilter())
                ->merge((new AppNameFilter()))
                ->merge((new AppURLFilter()));
        }

        if (($options['hasPath']) && (! $tmpFilter->has('iconPath')))
        {
            $tmpFilter->merge((new AppIconPathFilter()));
        }
        else if ((! $options['hasPath']) && ($tmpFilter->has('iconPath')))
        {
            $tmpFilter->remove("iconPath");
        }

        if (($options['hasIcon']) && (! $tmpFilter->has('icon')))
        {
            $tmpFilter->merge(new AppIconFilter());
        }
        else if ((! $options['hasIcon']) && ($tmpFilter->has('icon')))
        {
            $tmpFilter->remove("icon");
        }

        if (! $this->inputFilter)
        {
            $this->inputFilter = $tmpFilter;
        }

        return $tmpFilter;
    }

    /**
     * Sets App's inputFilter
     *
     * Throws error. App's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return App $this
     * @throws DomainException
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
