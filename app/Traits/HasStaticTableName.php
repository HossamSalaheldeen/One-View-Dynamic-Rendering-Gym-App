<?php

namespace App\Traits;

trait HasStaticTableName
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
