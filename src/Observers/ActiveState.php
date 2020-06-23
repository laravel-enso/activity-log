<?php

namespace LaravelEnso\ActivityLog\Observers;

use LaravelEnso\ActivityLog\Events\UpdatedActiveState as Event;
use LaravelEnso\ActivityLog\Services\Factory;
use LaravelEnso\Helpers\Contracts\Activatable;

class ActiveState
{
    public function updatedActiveState(Activatable $model)
    {
        (new Factory(new Event($model)))->create();
    }
}
