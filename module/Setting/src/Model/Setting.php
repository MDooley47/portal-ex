<?php

namespace Setting\Model;

use DomainException;
use Setting\InputFilter\DataFilter;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Setting
    implements HasSlugInterface
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
     * Get tab values as array.
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
     * Gets Setting's input filter.
     *
     * Returns the tab's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
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
     * Sets Setting's inputFilter.
     *
     * Throws error. Setting's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return Setting $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
