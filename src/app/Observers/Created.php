<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Services\Factory;
use LaravelEnso\ActivityLog\app\Events\Created as Event;

class Created
{
    public function created($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
