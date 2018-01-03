<?php

namespace Group\Model;

use DomainException;

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
     * Int for Group's id found in the db.
     */
    public $id;
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
        'id',
        'slug',
    ];

    /**
     * Get group values as array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
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
