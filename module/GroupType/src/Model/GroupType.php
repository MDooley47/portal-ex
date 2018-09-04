<?php

namespace GroupType\Model;

use DomainException;
use GroupType\InputFilter\NameFilter;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class GroupType
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for GroupType's id found in the db.
     */
    public $id;
    /**
     * String for GroupType's name.
     */
    public $name;
    /**
     * String for GroupType's description.
     */
    public $description;

    /**
     * InputFilter for GroupType's inputFilter.
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
     * Get app values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'name'        => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * Gets GroupType's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return GroupType $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets GroupType's inputFilter.
     *
     * Throws error. GroupType's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return GroupType $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
