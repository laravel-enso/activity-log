<?php

namespace LaravelEnso\ActivityLog\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\ActivityLog\Contracts\Loggable;
use LaravelEnso\ActivityLog\Contracts\ProvidesAttributes;
use LaravelEnso\ActivityLog\Facades\Logger;
use LaravelEnso\ActivityLog\Models\ActivityLog;

class Factory
{
    private Model $model;
    private Config $config;

    public function __construct(private Loggable $event)
    {
        $this->model = $event->model();
        $this->config = Logger::config($this->model);
    }

    public function create()
    {
        ActivityLog::create([
            'model_class' => get_class($this->model),
            'model_id' => $this->model->getKey(),
            'event' => $this->event->type(),
            'meta' => [
                'message' => $this->event->message(),
                'attributes' => $this->attributes(),
                'icon' => $this->event->icon(),
                'iconClass' => $this->event->iconClass(),
            ],
        ]);
    }

    private function attributes()
    {
        return [
            'user' => Auth::user() ? Auth::user()->person->name : 'N/A',
            'model' => $this->config->alias(),
            'label' => $this->label(),
        ] + $this->providedAttributes();
    }

    private function label()
    {
        return Collection::wrap(explode('.', $this->config->label()))
            ->reduce(fn ($label, $attribute) => $label->{$attribute}, $this->event->model());
    }

    private function providedAttributes()
    {
        return $this->event instanceof ProvidesAttributes
            ? $this->event->attributes()
            : [];
    }
}
