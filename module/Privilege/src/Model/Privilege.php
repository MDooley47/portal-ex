<?php

namespace Privilege\Model;

use DomainException;
use Model\Concerns\HasCast;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use Privilege\InputFilter\NameFilter;
use SessionManager\Session;
use SessionManager\Tables;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Privilege extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasSlug, HasCast, HasGuarded, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'privileges';
    public static $form = [
        'name' => [
            'type'     => 'text',
            'required' => true,
        ],
        'description' => [
            'type'     => 'textarea',
            'required' => false,
        ],
        'level' => [
            'type'     => 'number',
            'required' => true,
        ],
    ];

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

    public function privilegeCheck($user = null)
    {
        $user = getSlug($user ?? Session::getUser());

        return (new Tables())->getTable('userPrivileges')->hasPrivilege($user, 'sudo');

    }
}
