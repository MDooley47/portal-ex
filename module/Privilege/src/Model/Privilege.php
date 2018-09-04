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
     * Privilege's id found in the db.
     *
     * @var int
     */
    public $id;

    /**
     * Privilege's description.
     *
     * @var string
     */
    public $description;

    /**
     * Privilege's name.
     *
     * @var string
     */
    public $name;

    /**
     * Privilege's level.
     *
     * @var int
     */
    public $level;

    /**
     * Privilege's inputFilter.
     *
     * @var \Zend\InputFilter\InputFilter
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
     * @return \Zend\InputFilter\BaseInputFilter
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
     * Throws error. Privilege's inputFilter cannot be modified
     * by an outside entity.
     *
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
