<?php

namespace LaravelEnso\ActivityLog\Observers;

use LaravelEnso\ActivityLog\Events\Created as Event;
use LaravelEnso\ActivityLog\Services\Factory;

class Created
{
    public function created($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
