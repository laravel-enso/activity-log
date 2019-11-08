<?php

namespace LaravelEnso\ActivityLog\App\Events;

use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\App\Contracts\ProvidesAttributes;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Traits\IsLoggable;
use LaravelEnso\Helpers\app\Contracts\Activatable;

class UpdatedActiveState implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    private $model;

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
        return $this->model->isActive()
            ? 'is-success'
            : 'is-danger';
    }

    public function attributes(): array
    {
        return [
            'state' => $this->model->isActive() ? 'activated' : 'deactivated',
        ];
    }
}
