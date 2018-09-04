<?php

namespace Privilege\Model;

use DomainException;
use Privilege\InputFilter\NameFilter;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Privilege
    implements HasSlugInterface
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for Privilege's id found in the db.
     */
    public $id;
    /**
     * String for Privilege's name.
     */
    public $name;
    /**
     * String for Privilege's description.
     */
    public $description;

    /**
     * InputFilter for Privilege's inputFilter.
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
     * Get privilege values as array.
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
     * Gets Privilege's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return Privilege $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Privilege's inputFilter.
     *
     * Throws error. Privilege's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return Privilege $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
