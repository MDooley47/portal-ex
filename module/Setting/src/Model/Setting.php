<?php

namespace Setting\Model;

use DomainException;
use Model\Concerns\HasCast;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use SessionManager\Session;
use SessionManager\Tables;
use Setting\InputFilter\DataFilter;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Setting extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasSlug, HasCast, HasGuarded, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'settings';
    public static $form = [
        'data' => [
            'type'     => 'json',
            'required' => true,
        ],
    ];

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

    public function privilegeCheck($user = null)
    {
        $user = getSlug($user ?? Session::getUser());

        return (new Tables())->getTable('userPrivileges')->hasPrivilege($user, 'sudo');

    }
}
