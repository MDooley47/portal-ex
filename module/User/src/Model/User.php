<?php

namespace User\Model;

use DomainException;
use SessionManager\Tables;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use User\InputFilter\NameFilter;
use Zend\InputFilter\InputFilter;

class User implements HasSlugInterface
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for User's id found in the db.
     */
    public $id;
    /**
     * String for User's name.
     */
    public $name;
    /**
     * String for User's email.
     */
    public $email;

    /**
     * InputFilter for User's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'slug',
        'codist',
    ];

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
            'county'   => $this->county(),
            'district' => $this->district(),
            'building' => $this->building(),
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

    public function defaultTab()
    {
        return (new Tables())
            ->getTable('ownerTabs')
            ->getTabs($this->district())[0];
        //->getTab($this->codist);
    }

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
    public function county(): String
    {
        return explode('-', $this->codist)[0];
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
