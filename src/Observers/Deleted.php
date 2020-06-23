<?php

namespace LaravelEnso\ActivityLog\Observers;

use LaravelEnso\ActivityLog\Events\Deleted as Event;
use LaravelEnso\ActivityLog\Services\Factory;

class Deleted
{
    public function deleted($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
