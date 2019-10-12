<?php

namespace LaravelEnso\ActivityLog\app\Services;

use Illuminate\Support\Facades\Auth;
use LaravelEnso\ActivityLog\app\Facades\Logger;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;
use LaravelEnso\ActivityLog\App\Contracts\ProvidesAttributes;

class Factory
{
    private $event;
    private $model;
    private $config;

    public function __construct(Loggable $event)
    {
        $this->event = $event;
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
            'user' => Auth::user()->person->name,
            'model' => $this->config->alias(),
            'label' => $this->label(),
        ] + $this->providedAttributes();
    }

    private function label()
    {
        return collect(explode('.', $this->config->label()))
            ->reduce(function ($label, $attribute) {
                return $label->{$attribute};
            }, $this->event->model());
    }

    private function providedAttributes()
    {
        return $this->event instanceof ProvidesAttributes
            ? $this->event->attributes()
            : [];
    }
}
