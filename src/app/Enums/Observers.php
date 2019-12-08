<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\ActivityLog\app\Observers\ActiveState;
use LaravelEnso\ActivityLog\app\Observers\Created;
use LaravelEnso\ActivityLog\app\Observers\Deleted;
use LaravelEnso\ActivityLog\app\Observers\Updated;
use LaravelEnso\Enums\app\Services\Enum;

class Observers extends Enum
{
    protected static $data = [
        Events::Created => Created::class,
        Events::Updated => Updated::class,
        Events::Deleted => Deleted::class,
        Events::UpdatedActiveState => ActiveState::class,
    ];
}
