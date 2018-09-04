<?php

namespace Tab\Model;

use DomainException;
use SessionManager\Tables;
use Tab\InputFilter\NameFilter;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Tab
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * String for Tab's name.
     */
    public $name;
    /**
     * String for Tab's description.
     */
    public $description;

    /**
     * InputFilter for Tab's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'slug',
    ];

    public function getApps()
    {
        $tables = new Tables();

        return $tables->getTable('tabApps')->getApps($this->slug, ['type' => 'tab']);
    }

    /**
     * Get tab values as array.
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

    public function getGroup()
    {
        return (new Tables())
            ->getTable('owerTabs')
            ->getGroup($this->slug);
    }

    /**
     * Gets Tab's input filter.
     *
     * Returns the tab's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
     * @return Tab $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Tab's inputFilter.
     *
     * Throws error. Tab's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return Tab $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
