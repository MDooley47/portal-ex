<?php

namespace GroupType\Model;

use DomainException;
use GroupType\InputFilter\NameFilter;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class GroupType extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasSlug, HasGuarded, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'groupTypes';
    public static $form = [
        'name' => [
            'type' => 'text',
            'required' => true,
        ],
        'description' => [
            'type' => 'textarea',
            'required' => false,
        ],
        'level' => [
            'type' => 'number',
            'required' => true,
        ],
    ];


    /**
     * InputFilter for GroupType's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
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
