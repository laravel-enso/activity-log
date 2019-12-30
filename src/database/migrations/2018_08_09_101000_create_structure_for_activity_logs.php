<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForActivityLogs extends Migration
{
    protected $permissions = [
        ['name' => 'core.activityLogs.index', 'description' => 'Show index for activity log', 'type' => Types::Read, 'is_default' => false],
    ];

    protected $menu = [
        'name' => 'Activity Log', 'icon' => 'shoe-prints', 'route' => 'core.activityLogs.index', 'order_index' => 900, 'has_children' => false,
    ];
}
