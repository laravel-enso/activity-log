<?php

namespace LaravelEnso\ActivityLog\Events;

use LaravelEnso\ActivityLog\Contracts\Loggable;
use LaravelEnso\ActivityLog\Contracts\ProvidesAttributes;
use LaravelEnso\ActivityLog\Enums\Events;
use LaravelEnso\ActivityLog\Traits\IsLoggable;
use LaravelEnso\Helpers\Contracts\Activatable;

class UpdatedActiveState implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    private Activatable $model;

    public function __construct(Activatable $model)
    {
        $this->model = $model;
    }

    public function type(): int
    {
        return Events::UpdatedActiveState;
    }

    public function message()
    {
        return ':user :state :model :label';
    }

    public function icon(): string
    {
        return $this->model->isActive() ? 'check' : 'ban';
    }

    public function iconClass(): string
    {
        return $this->model->isActive() ? 'is-success' : 'is-danger';
    }

    public function attributes(): array
    {
        return ['state' => $this->model->isActive() ? 'activated' : 'deactivated'];
    }
}
