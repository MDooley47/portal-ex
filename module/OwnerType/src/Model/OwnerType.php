<?php

namespace OwnerType\Model;

use DomainException;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use OwnerType\InputFilter\NameFilter;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class OwnerType extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasSlug, HasGuarded, ExchangeArray, QueryBuilder;

    public $id;
    public $slug;
    public $name;
    public $description;

    public static $primaryKey = 'slug';
    protected static $table = 'ownerTypes';
    public static $form = [
        'name' => [
            'type'     => 'text',
            'required' => true,
        ],
        'description' => [
            'type'     => 'textarea',
            'required' => false,
        ],
    ];

    /**
     * InputFilter for OwnerType's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'slug',
    ];

    public function __construct(array $attributes = [])
    {
        $this->slug = $attributes[0]['slug'];
        $this->name = $attributes[0]['name'];
        $this->description = $attributes[0]['description'];
    }

    /**
     * Get app values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug'        => $this->slug,
            'name'        => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * Gets OwnerType's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return OwnerType $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets OwnerType's inputFilter.
     *
     * Throws error. OwnerType's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return OwnerType $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
