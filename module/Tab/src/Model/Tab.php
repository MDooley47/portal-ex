<?php

namespace Tab\Model;

use DomainException;

use Traits\Models\HasSlug;
use Traits\Models\HasGuarded;
use Traits\Models\ExchangeArray;

use Tab\InputFilter\NameFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Tab
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for Tab's id found in the db.
     */
    public $id;
    /**
     * String for Tab's name.
     */
    public $name;
    /**
     * String for Tab's description.
     */
    public $description;

    /**
     * InputFilter for Tab's inputFilter.
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
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * Gets Tab's input filter
     *
     * Returns the tab's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param Array $options
     * @return Tab $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Tab's inputFilter
     *
     * Throws error. Tab's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return Tab $this
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
