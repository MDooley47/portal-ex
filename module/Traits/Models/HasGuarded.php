<?php

namespace Traits\Models;

trait HasGuarded
{
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
}
?>
