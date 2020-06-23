<?php

namespace LaravelEnso\ActivityLog\Observers;

use LaravelEnso\ActivityLog\Events\Updated as Event;
use LaravelEnso\ActivityLog\Services\Factory;

class Updated
{
    public function updated($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
