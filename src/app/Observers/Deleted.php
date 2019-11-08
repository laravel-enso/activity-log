<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Events\Deleted as Event;
use LaravelEnso\ActivityLog\app\Services\Factory;

class Deleted
{
    public function deleted($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
