<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

if ( !function_exists('getAttributeStringByReflection') ) {
    function getAttributeStringByReflection($reflectorClass, $property)
    {
        $reflector = new \ReflectionClass($reflectorClass);
        $constant  = '';

        foreach ( $reflector->getConstants() as $constant => $value ) {
            if ( $value == $property ) {
                $constant = str_replace('_', ' ', Str::title($constant));
                break;
            }
        }

        return $constant;
    }
}

if (!function_exists('getPermissionName')) {

    function getPermissionName($permission,$modelClassName)
    {
        $tableName = App::make($modelClassName)->getTable();
        return $permission .'-'. Str::Slug($tableName);
    }
}

