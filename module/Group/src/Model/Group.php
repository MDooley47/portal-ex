<?php

namespace Group\Model;

use DomainException;
use Group\InputFilter\NameFilter;
use Model\Concerns\HasCast;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Model;
use SessionManager\Tables;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Group extends Model implements HasSlugInterface
{
    use Boot, HasCast, HasSlug, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'groups';
    public static $form = [
        'name' => [
            'type'     => 'text',
            'required' => true,
        ],
        'description' => [
            'type'     => 'textarea',
            'required' => false,
        ],
        'grouptype' => [
            'type'     => 'grouptype',
            'label'    => 'GroupType',
            'required' => true,
        ],
    ];

    /**
     * String for district's logo file.
     */
    public $logoFilename;

    /**
     * String for Group's brand/header color.
     */
    public $themeColor;

    /**
     * String for Group's background color.
     */
    public $backgroundColor;

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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Get associated tabs.
     *
     * @return array
     */
    public function getTabs()
    {
        $tables = new Tables();

        //dd($tables->getTable('ownerType'));

        return $tables
            ->getTable('ownerTabs')
            ->getTabs($this->slug, [
                'type' => $tables
                    ->getTable('ownerType')
                    ->getType('group', ['type' => 'name'])
                    ->name,
            ]);
    }

    /**
     * Boolean representation of if the group has at least one tab.
     *
     * @return bool
     */
    public function hasTab(): bool
    {
        return !empty($this->getTabs());
    }

    /**
     * Get group values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'slug'            => $this->slug,
            'name'            => $this->name,
            'description'     => $this->description,
            'grouptype'       => $this->grouptype,
            'logoFilename'    => $this->logoFilename,
            'themeColor'      => $this->themeColor,
            'backgroundColor' => $this->backgroundColor,
        ];
    }

    /**
     * Gets Group's input filter.
     *
     * Returns the group's inputFilter.
     * Creates the inputFilter if it does not exist.
     *
     * @param array $options
     *
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
     * Sets Group's inputFilter.
     *
     * Throws error. Group's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return Group $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
