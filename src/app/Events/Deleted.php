<?php

namespace LaravelEnso\ActivityLog\App\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Traits\IsLoggable;

class Deleted implements Loggable
{
    use IsLoggable;

    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function type(): int
    {
        return Events::Deleted;
    }

    public function message()
    {
        return ':user deleted :model :label';
    }

    public function icon(): string
    {
        return 'trash-alt';
    }

    public function iconClass(): string
    {
        return 'is-danger';
    }
}
