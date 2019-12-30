<?php

namespace LaravelEnso\ActivityLog\App\Observers;

use LaravelEnso\ActivityLog\App\Events\Created as Event;
use LaravelEnso\ActivityLog\App\Services\Factory;

class Created
{
    public function created($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
