<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Services\Factory;
use LaravelEnso\ActivityLog\app\Events\Updated as Event;

class Updated
{
    public function updated($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
