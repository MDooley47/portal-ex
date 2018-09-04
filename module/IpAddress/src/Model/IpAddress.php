<?php

namespace IpAddress\Model;

use DomainException;
use IpAddress\InputFilter\NameFilter;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class IpAddress
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for IpAddress's id found in the db.
     */
    public $id;
    /**
     * String for IpAddress's name.
     */
    public $name;
    /**
     * String for IpAddress's description.
     */
    public $description;

    /**
     * InputFilter for IpAddress's inputFilter.
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
     * Get ipAddress values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug'        => $this->slug,
            'name'        => $this->name,
            'description' => $this->description,
            'ip'          => $this->ip,
        ];
    }

    /**
     * Gets IpAddress's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return IpAddress $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets IpAddress's inputFilter.
     *
     * Throws error. IpAddress's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return IpAddress $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
