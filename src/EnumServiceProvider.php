<?php

namespace LaravelEnso\ActivityLog;

use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Enums\Observers;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'loggableEvents' => Events::class,
    ];
}
