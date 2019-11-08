<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\App\Events\UpdatedActiveState as Event;
use LaravelEnso\ActivityLog\app\Services\Factory;
use LaravelEnso\Helpers\app\Contracts\Activatable;

class ActiveState
{
    public function updatedActiveState(Activatable $model)
    {
        (new Factory(new Event($model)))->create();
    }
}
