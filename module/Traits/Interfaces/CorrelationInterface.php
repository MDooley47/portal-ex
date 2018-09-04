<?php

namespace Traits\Interfaces;

interface CorrelationInterface
{
    public function correlationExists($element1, $element2, $options);

    public function addCorrelation($element1, $element2, $options);
}
