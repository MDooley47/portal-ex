<?php

namespace App\Model;

use App\InputFilter\IconFilter;
use App\InputFilter\IconPathFilter;
use App\InputFilter\NameFilter;
use App\InputFilter\TabFilter;
use App\InputFilter\URLFilter;
use DomainException;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasGuarded;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class App implements HasSlugInterface
{
    use HasSlug, HasGuarded, ExchangeArray;
    /**
     * Int for App's id found in the db.
     */
    public $id;
    /**
     * String for App's name.
     */
    public $name;
    /**
     * String for App's destination url.
     */
    public $url;
    /**
     * String for App's iconPath on the local filesystem.
     */
    public $iconPath;
    /**
     * Int for the App's version. Mainly used for cache breaking.
     */
    public $version;
    /**
     * InputFilter for App's inputFilter.
     */
    protected $inputFilter;

    /**
     * Static variable containing values users cannot change.
     */
    protected static $guarded = [
        'id',
        'slug',
        'version',
    ];

    /**
     * Get app values as array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'id'       => $this->id,
            'slug'     => $this->slug,
            'name'     => $this->name,
            'url'      => $this->url,
            'iconPath' => $this->iconPath,
            'version'  => $this->version,
        ];
    }

    /**
     * Gets App's input filter.
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     * Adds/Removes the iconPath filter depending upon
     * the passed in boolean value $options['hasPath'].
     * Adds/Removes the icon filter depending upon
     * the passed in boolean value $options['hasIcon'].
     *
     * @param array $options
     *
     * @return App $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter())
            ->merge(new URLFilter())
            ->merge(new TabFilter(false));

        if (($options['hasPath']) && (!$tmpFilter->has('iconPath'))) {
            $tmpFilter->merge(new IconPathFilter());
        } elseif ((!$options['hasPath']) && ($tmpFilter->has('iconPath'))) {
            $tmpFilter->remove('iconPath');
        }

        if (($options['hasIcon']) && (!$tmpFilter->has('icon'))) {
            $tmpFilter->merge(new IconFilter());
        } elseif ((!$options['hasIcon']) && ($tmpFilter->has('icon'))) {
            $tmpFilter->remove('icon');
        }

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Sets App's inputFilter.
     *
     * Throws error. App's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @throws DomainException
     *
     * @return App $this
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
}
