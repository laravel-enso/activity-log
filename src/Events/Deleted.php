<?php

namespace LaravelEnso\ActivityLog\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\Contracts\Loggable;
use LaravelEnso\ActivityLog\Enums\Events;
use LaravelEnso\ActivityLog\Traits\IsLoggable;

class Deleted implements Loggable
{
    use IsLoggable;

    public function __construct(private Model $model)
    {
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
