<?php

namespace App\Enums;


abstract class PermissionEnum
{
    const VIEW_ANY = 'view-any';
    const VIEW = 'view';
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const BAN = 'ban';
}
