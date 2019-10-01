<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;
use LaravelEnso\ActivityLog\app\Observers\Created;
use LaravelEnso\ActivityLog\app\Observers\Deleted;
use LaravelEnso\ActivityLog\app\Observers\Updated;

class Observers extends Enum
{
    protected static function attributes()
    {
        return [
            Events::Created => Created::class,
            Events::Updated => Updated::class,
            Events::Deleted => Deleted::class,
        ];
    }
}
