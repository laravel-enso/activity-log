<?php

namespace LaravelEnso\ActivityLog\Enums;

use LaravelEnso\ActivityLog\Observers\ActiveState;
use LaravelEnso\ActivityLog\Observers\Created;
use LaravelEnso\ActivityLog\Observers\Deleted;
use LaravelEnso\ActivityLog\Observers\Updated;
use LaravelEnso\Enums\Services\Enum;

class Observers extends Enum
{
    protected static array $data = [
        Events::Created => Created::class,
        Events::Updated => Updated::class,
        Events::Deleted => Deleted::class,
        Events::UpdatedActiveState => ActiveState::class,
    ];
}
