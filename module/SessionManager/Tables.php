<?php

namespace SessionManager;

use Traits\HasTables;

class Tables
{
    use HasTables;

    public function __construct($tables_array)
    {
        $this->addTableArray($tables_array);
    }
}
?>
