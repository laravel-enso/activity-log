<?php

namespace LaravelEnso\ActivityLog\App\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Traits\IsLoggable;

class Created implements Loggable
{
    use IsLoggable;

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function type(): int
    {
        return Events::Created;
    }

    public function message()
    {
        return ':user created :model :label';
    }

    public function icon(): string
    {
        return 'plus';
    }

    public function iconClass(): string
    {
        return 'is-success';
    }
}
