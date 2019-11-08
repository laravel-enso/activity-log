<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Events\Updated as Event;
use LaravelEnso\ActivityLog\app\Services\Factory;

class Updated
{
    public function updated($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
