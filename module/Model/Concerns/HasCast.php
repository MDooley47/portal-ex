<?php

namespace Model\Concerns;

trait HasCast {
    public static function cast(array $attributes) {
        return new self($attributes);
    }
}