<?php

namespace LaravelEnso\ActivityLog;

use LaravelEnso\ActivityLog\Enums\Events;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'loggableEvents' => Events::class,
    ];
}
