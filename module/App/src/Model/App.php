<?php

namespace App\Model;

use App\InputFilter\IconFilter;
use App\InputFilter\IconPathFilter;
use App\InputFilter\NameFilter;
use App\InputFilter\TabFilter;
use App\InputFilter\URLFilter;
use DomainException;
use Model\Concerns\HasCast;
use Model\Concerns\QueryBuilder;
use Model\Concerns\QuickModelBoot as Boot;
use Model\Contracts\Bootable;
use Model\Model;
use SessionManager\Session;
use SessionManager\Tables;
use Traits\Interfaces\HasSlug as HasSlugInterface;
use Traits\Models\ExchangeArray;
use Traits\Models\HasSlug;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class App extends Model implements HasSlugInterface, Bootable
{
    use Boot, HasCast, HasSlug, ExchangeArray, QueryBuilder;

    public static $primaryKey = 'slug';
    protected static $table = 'apps';
    public static $form = [
        'name' => [
            'type'     => 'text',
            'required' => true,
        ],
        'url' => [
            'type'     => 'url',
            'required' => true,
        ],
        'icon' => [
            'type'     => 'file',
            'required' => true,
        ],
        'iconPath' => [
          'type'     => 'text',
          'required' => false,
          'readonly' => true,
          'label'    => 'Icon Path',
        ],
    ];

    /**
     * InputFilter for App's inputFilter.
     */
    protected $inputFilter;

    /**
     * App constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function saveIconFromBase64($base64Image)
    {
        $target = realpath(getenv('storage_path').'/images/');

        $base64_parts = explode(',', $base64Image);
        $base64Prefix = $base64_parts[0];
        $base64Image = $base64_parts[1];

        $image_extension = explode('/', explode(';', $base64Prefix)[0])[1];

        $image_contents = base64_decode($base64Image);

        do {
            $filename = $target.uniqid('/app-', true).'.'.$image_extension;
        } while (file_exists($filename));

        file_put_contents($filename, $image_contents);

        return removeBasePath($filename);
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
     * @return \Zend\InputFilter\BaseInputFilter
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

    public function getTabs()
    {
        return (new Tables())->getTable('tabApps')->getTabsByAppCorrelation($this->slug);
    }

    public function privilegeCheck($user = null)
    {
        // A user needs auth privilege to at least one group that has
        // ownership of a tab that has ownership of the app.
        $user = getSlug($user ?? Session::getUser());

        $privilege = false;

        foreach($this->getTabs() as $tab) {
            if ($tab->privilegeCheck($user)) {
                $privilege = true;
                break;
            }
        }

        return $privilege;
    }
}
