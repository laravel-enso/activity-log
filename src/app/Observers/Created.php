<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Events\Created as Event;
use LaravelEnso\ActivityLog\app\Services\Factory;

class Created
{
    public function created($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
