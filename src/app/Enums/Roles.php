<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;
use LaravelEnso\RoleManager\app\Models\Role;

class Roles extends Enum
{
    protected static function attributes()
    {
        return Role::pluck('name', 'id')->toArray();
    }
}
