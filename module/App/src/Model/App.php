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
    public $id;
    public $slug;
    public $name;
    public $url;
    public $iconPath;
    public $version;
    protected $inputFilter;

    /**
     * Values users cannot change.
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
     * @param array $data
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

    public static function generateSlug($len = 6,
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789")
    {
        $ranString = "";
        for ($i = 0; $i < $len; $i++) $ranString .= $charset[mt_rand(0, strlen($charset) - 1)];
        return $ranString;
    }

    public static function sanitizeGuarded(&$data)
    {
        foreach ($data as $key=>&$value)
        {
            if (in_array($key, self::$guarded))
            {
                $value = null;
            }
        }
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
