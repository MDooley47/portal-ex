<?php

namespace Setting\Model;

use DomainException;

use Traits\Models\HasSlug;
use Traits\Models\HasGuarded;
use Traits\Models\ExchangeArray;

use Setting\InputFilter\DataFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Setting
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for Setting's id found in the db.
     */
    public $id;
    /**
     * String for Setting's name.
     */
    public $name;
    /**
     * String for Setting's description.
     */
    public $description;

    /**
     * InputFilter for Setting's inputFilter.
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
     * Get tab values as array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug' => $this->slug,
            'data' => $this->data,
        ];
    }

    /**
     * Gets Setting's input filter
     *
     * Returns the tab's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param Array $options
     * @return Setting $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new DataFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Setting's inputFilter
     *
     * Throws error. Setting's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return Setting $this
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
