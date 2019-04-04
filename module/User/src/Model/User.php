<?php

namespace User\Model;

use DomainException;
use Model\Concerns\HasCast;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use SessionManager\Tables;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use User\InputFilter\NameFilter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class User extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasCast, HasSlug, HasGuarded, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'users';
    public static $form = [
        'name' => [
            'type'     => 'text',
            'required' => true,
        ],
        'email' => [
            'type'     => 'email',
            'required' => true,
        ],
        'codist' => [
            'type'     => 'text',
            'label'    => 'County District Number',
            'required' => false,
        ],
    ];

    /**
     * User constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * String for the Nebraska county-district number (CO-DST-BLDG format).
     */
    public $codist;

    /**
     * InputFilter for User's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'slug',
    ];

    /**
     *   COUNTY CODE 2 DIGITS
     * DISTRICT CODE 4 DIGITS
     * BUILDING CODE 3 DIGITS.
     *
     *  EXAMPLE: 06-8473-729
     *   COUNTY: 06
     * DISTRICT: 8473
     * BUILDING: 729
     *
     * note: hyphen's are assumed to be part of the codist.
     */
    public function building($options = []): String
    {
        arrayValueDefault('composite-key', $options, true);

        if ($options['composite-key']) {
            $out = $this->codist;
        } else {
            $out = explode('-', $this->codist)[2];
        }

        return $out;
    }

    public function county(): String
    {
        return explode('-', $this->codist)[0];
    }

    public function defaultTab()
    {
        $tables = new Tables();
        $ownerTabs = $tables->getTable('ownerTabs');
        $tab = $ownerTabs->getTabs($this->district())[0];

        return $tab;
        //->getTab($this->codist);
    }

    public function district($options = []): String
    {
        arrayValueDefault('composite-key', $options, true);

        $codist = explode('-', $this->codist);

        if ($options['composite-key']) {
            $out = $codist[0].'-'.$codist[1];
        } else {
            $out = $codist[1];
        }

        return $out;
    }

    /**
     * Get app values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug'     => $this->slug,
            'name'     => $this->name,
            'email'    => $this->email,
            'codist'   => $this->codist,
        ];
    }

    /**
     * Gets User's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return User $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     *  Returns the relative path (string) to the user's group logo.
     */
    public function getLogoFilename()
    {
        $fn = '';
        // look up the group based on the user's CDN
        $tables = new Tables();
        $groupsTable = $tables->getTable('group');
        $group = $groupsTable->getGroup($this->district());

        if ($group) {
            $fn = $group->logoFilename;
        }

        return $fn;
    }

    /**
     *  Returns the background brand/header color to use for this user in
     *  6-digit hex format (example: #FF7700).
     */
    public function getThemeColor()
    {
        $color = '';
        // look up the group based on the user's CDN
        $tables = new Tables();
        $groupsTable = $tables->getTable('group');
        $group = $groupsTable->getGroup($this->district());

        if ($group) {
            $color = $group->themeColor;
        }

        return $color;
    }

    /**
     * Sets User's inputFilter.
     *
     * Throws error. User's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return User $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
