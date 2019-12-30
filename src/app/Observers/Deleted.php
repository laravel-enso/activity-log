<?php

namespace LaravelEnso\ActivityLog\App\Observers;

use LaravelEnso\ActivityLog\App\Events\Deleted as Event;
use LaravelEnso\ActivityLog\App\Services\Factory;

class Deleted
{
    public function deleted($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
