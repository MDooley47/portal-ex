<?php

namespace Traits\Models;

trait HasSlug
{
    /**
     * String for User's slug found in the db.
     */
    public $slug;

    /**
     * Static function for generating a random slug.
     *
     * @param int    $len     Length of the desired slug. Default Value is 6.
     * @param string $charset Set of characters to choose from. Default value
     *                        is alphanumeric.
     *
     * @return string $ranString
     */
    public static function generateSlug($len = 6,
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $ranString = '';
        for ($i = 0; $i < $len; $i++) {
            $ranString .= $charset[mt_rand(0, strlen($charset) - 1)];
        }

        return $ranString;
    }
}
