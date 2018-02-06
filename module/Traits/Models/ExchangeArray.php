<?php

namespace Traits\Models;

trait ExchangeArray {
    /**
     * Sets Model's values
     *
     * Takes in dictionary and set instance variables.
     *
     * @param dictionary $data
     * @return Model $this
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->{$key} = !empty($value) ? $value : (int) null;
        }

        return $this;
    }
}


?>
