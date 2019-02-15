<?php

namespace Traits\Tables;

trait HasColumns {

    public function getColumns() {
        return $this->info(Zend_Db_Table_Abstract::COLS);
    }
}