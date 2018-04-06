<?php

namespace Configuration\Model;

use DomainException;

use Traits\Models\HasSlug;
use Traits\Models\HasGuarded;
use Traits\Models\ExchangeArray;

use Configuration\InputFilter\NameFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Configuration
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for Configuration's id found in the db.
     */
    public $id;
    /**
     * String for Configuration's name.
     */
    public $name;
    /**
     * String for Configuration's description.
     */
    public $description;

    /**
     * InputFilter for Configuration's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'id',
        'slug',
    ];

    /**
     * Get privilege values as array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [];
    }

    /**
     * Gets Configuration's input filter
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param Array $options
     * @return Configuration $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Configuration's inputFilter
     *
     * Throws error. Configuration's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return Configuration $this
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