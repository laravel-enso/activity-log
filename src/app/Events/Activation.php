<?php

namespace LaravelEnso\ActivityLog\App\Events;

use App\Enums\Events;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\app\Traits\IsLoggable;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\App\Contracts\ProvidesAttributes;

class Activation implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function type(): int
    {
        return Events::Activation;
    }

    public function message(): string
    {
        return ':user makes :model :label :activation';
    }

    public function icon(): string
    {
        return $this->model->isActive() ? 'check' : 'ban';
    }

    public function attributes(): array
    {
        return [
            'activation' => $this->model->isActive() ? 'active' : 'inactive',
        ];
    }
}
