<?php

namespace Group\Model;

use DomainException;

use SessionManager\Tables;

use Traits\Models\HasSlug;
use Traits\Models\HasGuarded;
use Traits\Models\ExchangeArray;

use Group\InputFilter\NameFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Group
{
    use HasSlug, HasGuarded, ExchangeArray;

    /**
     * String for Group's name.
     */
    public $name;
    /**
     * String for Group's description.
     */
    public $description;

    /**
     * InputFilter for Group's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'slug',
    ];

    public function getTabs()
    {
        $tables = new Tables();

        return $tables
            ->getTable('ownerTabs')
            ->getTabs($this->slug, [
                'type' => $tables
                    ->getTable('ownerTypes')
                    ->getType('group', ['type' => 'name']),
            ]);
    }

    /**
     * Get group values as array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'grouptype' => $this->grouptype,
        ];
    }

    /**
     * Gets Group's input filter
     *
     * Returns the group's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param Array $options
     * @return Group $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter());

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets Group's inputFilter
     *
     * Throws error. Group's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return Group $this
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
