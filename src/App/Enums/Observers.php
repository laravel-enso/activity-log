<?php

namespace LaravelEnso\ActivityLog\App\Enums;

use LaravelEnso\ActivityLog\App\Observers\ActiveState;
use LaravelEnso\ActivityLog\App\Observers\Created;
use LaravelEnso\ActivityLog\App\Observers\Deleted;
use LaravelEnso\ActivityLog\App\Observers\Updated;
use LaravelEnso\Enums\App\Services\Enum;

class Observers extends Enum
{
    protected static array $data = [
        Events::Created => Created::class,
        Events::Updated => Updated::class,
        Events::Deleted => Deleted::class,
        Events::UpdatedActiveState => ActiveState::class,
    ];
}
