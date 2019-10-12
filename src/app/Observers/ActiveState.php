<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Services\Factory;
use LaravelEnso\ActivityLog\App\Events\UpdatedActiveState as Event;

class ActiveState
{
    public function updatedActiveState($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
