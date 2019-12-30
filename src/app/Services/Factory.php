<?php

namespace LaravelEnso\ActivityLog\App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\App\Contracts\ProvidesAttributes;
use LaravelEnso\ActivityLog\App\Facades\Logger;
use LaravelEnso\ActivityLog\App\Models\ActivityLog;

class Factory
{
    private Loggable $event;
    private Model $model;
    private array $config;

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
        return (new Collection(explode('.', $this->config->label())))
            ->reduce(fn ($label, $attribute) => $label->{$attribute}, $this->event->model());
    }

    private function providedAttributes()
    {
        return $this->event instanceof ProvidesAttributes
            ? $this->event->attributes()
            : [];
    }
}
