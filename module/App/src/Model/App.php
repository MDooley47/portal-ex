<?php

namespace App\Model;

use App\InputFilter\NameFilter;
use App\InputFilter\URLFilter;
use App\InputFilter\IconFilter;
use App\InputFilter\IconPathFilter;

use DomainException;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class App
{
    /**
     * Int for App's id found in the db.
     */
    public $id;
    /**
     * String for App's slug found in the db.
     */
    public $slug;
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
     * Sets App's values
     *
     * Takes in dictionary and set instance variables.
     *
     * @param dictionary $data
     * @return App $this
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->{$key} = !empty($value) ? $value : (int) null;
        }

        return $this;
    }

    /**
     * Get app values as array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'url' => $this->url,
            'iconPath' => $this->iconPath,
            'version' => $this->version,
        ];
    }

    /**
     * Gets App's input filter
     *
     * Returns the app's inputFilter.
     * Creates the inputFilter if it does not exist.
     * Adds/Removes the iconPath filter depending upon
     * the passed in boolean value $options['hasPath'].
     * Adds/Removes the icon filter depending upon
     * the passed in boolean value $options['hasIcon'].
     *
     * @param Array $options
     * @return App $this
     */
    public function getInputFilter($options = [])
    {
        $tmpFilter = (new InputFilter())
            ->merge(new NameFilter())
            ->merge(new URLFilter());

        if (($options['hasPath']) && (! $tmpFilter->has('iconPath')))
        {
            $tmpFilter->merge(new IconPathFilter());
        }
        else if ((! $options['hasPath']) && ($tmpFilter->has('iconPath')))
        {
            $tmpFilter->remove("iconPath");
        }

        if (($options['hasIcon']) && (! $tmpFilter->has('icon')))
        {
            $tmpFilter->merge(new IconFilter());
        }
        else if ((! $options['hasIcon']) && ($tmpFilter->has('icon')))
        {
            $tmpFilter->remove("icon");
        }

        $this->inputFilter = $tmpFilter;

        return $tmpFilter;
    }

    /**
     * Static function for generating a random slug.
     *
     * @param int $len Length of the desired slug. Default Value is 6.
     * @param String $charset Set of characters to choose from. Default value
     * is alphanumeric.
     * @return String $ranString
     */
    public static function generateSlug($len = 6,
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789")
    {
        $ranString = "";
        for ($i = 0; $i < $len; $i++) $ranString .= $charset[mt_rand(0, strlen($charset) - 1)];
        return $ranString;
    }

    /**
     * Static function for sanitizing an array.
     *
     * Takes in dictionary and removes keys found in $this->guarded.
     *
     * @param dictionary &$data
     * @return dictionary $data
     */
    public static function sanitizeGuarded(&$data)
    {
        foreach ($data as $key => $value)
        {
            if (in_array($key, self::$guarded))
            {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Sets App's inputFilter
     *
     * Throws error. App's inputFilter cannot be modifed
     * by an outside enity.
     *
     * @return App $this
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
