<?php

namespace SessionManager;

use Traits\HasTables;

class Tables
{
    use HasTables;

    public function __construct($additionalTables = null)
    {
        $this->init($additionalTables);
    }
}
