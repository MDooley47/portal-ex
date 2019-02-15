<?php

namespace Traits\Models;

trait HasGuarded
{
    /**
     * Static function for sanitizing an array.
     *
     * Takes in dictionary and removes keys found in $this->guarded.
     *
     * @param dictionary|Model &$data
     *
     * @return dictionary $data
     */
    public static function sanitizeGuarded(&$data)
    {
        if (property_exists(self::class, '$guarded')) {
            return $data;
        } elseif (is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, self::$guarded)) {
                    unset($data[$key]);
                }
            }
        } elseif (method_exists($data, 'getArrayCopy')) {
            return $data = self::sanitizeGuarded($data->getArrayCopy());
        }

        return $data;
    }
}
