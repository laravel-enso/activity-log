<?php

namespace LaravelEnso\ActivityLog\App\Observers;

use LaravelEnso\ActivityLog\App\Events\Updated as Event;
use LaravelEnso\ActivityLog\App\Services\Factory;

class Updated
{
    public function updated($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
