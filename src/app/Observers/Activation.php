<?php

namespace LaravelEnso\ActivityLog\app\Observers;

use LaravelEnso\ActivityLog\app\Services\Factory;
use LaravelEnso\ActivityLog\App\Events\Activation as Event;

class Activation
{
    public function activation($model)
    {
        (new Factory(new Event($model)))->create();
    }
}
