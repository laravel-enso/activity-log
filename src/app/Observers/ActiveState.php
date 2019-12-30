<?php

namespace LaravelEnso\ActivityLog\App\Observers;

use LaravelEnso\ActivityLog\App\Events\UpdatedActiveState as Event;
use LaravelEnso\ActivityLog\App\Services\Factory;
use LaravelEnso\Helpers\App\Contracts\Activatable;

class ActiveState
{
    public function updatedActiveState(Activatable $model)
    {
        (new Factory(new Event($model)))->create();
    }
}
