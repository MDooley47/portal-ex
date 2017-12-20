<?php

namespace App\Model;

use App\InputFilter\AppNameFilter;
use App\InputFilter\AppURLFilter;
use App\InputFilter\AppIconFilter;
use App\InputFilter\AppIconPathFilter;

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
     * Sets App's values
     *
     * Takes in dictionary and set instance variables.
     *
     * @return App $this
     */
    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->slug = !empty($data['slug']) ? $data['slug'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->iconPath = !empty($data['iconPath']) ? $data['iconPath'] : null;
        $this->version = !empty($data['version']) ? (int) $data['version'] : 0;

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
            ->merge(new AppNameFilter())
            ->merge(new AppURLFilter());

        if (($options['hasPath']) && (! $tmpFilter->has('iconPath')))
        {
            $tmpFilter->merge(new AppIconPathFilter());
        }
        else if ((! $options['hasPath']) && ($tmpFilter->has('iconPath')))
        {
            $tmpFilter->remove("iconPath");
        }

        if (($options['hasIcon']) && (! $tmpFilter->has('icon')))
        {
            $tmpFilter->merge(new AppIconFilter());
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
