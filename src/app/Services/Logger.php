<?php

namespace LaravelEnso\ActivityLog\app\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

class Logger
{
    private $model;
    private $loggableChanges;
    private $before;
    private $after;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->loggableChanges = $this->loggableChanges();
    }

    public function onCreated()
    {
        $this->log(Events::Created);
    }

    public function onUpdated()
    {
        if ($this->loggableChanges->isEmpty()) {
            return;
        }

        $this->after = $this->after();
        $this->before = $this->before();

        $this->parse()->log(Events::Updated);
    }

    public function onDeleted()
    {
        $this->log(Events::Deleted);
    }

    public function onEvent($message, $icon, $eventType = null)
    {
        $eventType = $eventType ?? Events::Custom;
        $this->log($eventType, $message, $icon);
    }

    private function log($event, $message = null, $icon = null)
    {
        ActivityLog::create([
            'model_class' => get_class($this->model),
            'model_id' => $this->model->getKey(),
            'event' => $event,
            'meta' => [
                'label' => $this->getLoggableLabel(),
                'before' => $this->before,
                'after' => $this->after,
                'message' => $message,
                'icon' => $icon,
                'morphable' => $this->getMorphable(),
                'relation' => $this->getRelation(),
            ],
        ]);
    }

    private function parse()
    {
        collect($this->loggableChanges)
            ->keys()
            ->each(function ($key) {
                if (! isset($this->model->getLoggable()[$key])) {
                    return;
                }

                if (is_array($this->model->getLoggable()[$key])) {
                    $this->readRelation($key);

                    return;
                }

                if (class_exists($this->model->getLoggable()[$key])) {
                    $this->readEnum($key);

                    return;
                }

                $this->updateKey($key);
            });

        return $this;
    }

    private function readRelation($key)
    {
        $class = key($this->model->getLoggable()[$key]);
        $attribute = $this->model->getLoggable()[$key][$class];

        $this->before[$key] = $class::find($this->before[$key])->{$attribute};
        $this->after[$key] = $class::find($this->after[$key])->{$attribute};
    }

    private function readEnum($key)
    {
        $this->before[$key] = $this->model
            ->getLoggable()[$key]::get($this->before[$key]);

        $this->after[$key] = $this->model
            ->getLoggable()[$key]::get($this->after[$key]);
    }

    private function getLoggableLabel()
    {
        return collect(explode('.', $this->model->getLoggableLabel()))
            ->reduce(function ($label, $attribute) {
                return $label = $label->{$attribute};
            }, $this->model);
    }

    private function getRelation()
    {
        $config = $this->model->getLoggableRelation();

        if (! $config) {
            return;
        }

        $relation = key($config);
        $modelClass = get_class($this->model->{$relation});
        $attribute = $config[$relation];

        return [
            'model_class' => $modelClass,
            'label' => $this->model->{$relation}->{$attribute},
        ];
    }

    private function getMorphable()
    {
        $config = $this->model->getLoggableMorph();

        if (! $config) {
            return;
        }

        $morphable = key($config);
        $modelClass = get_class($this->model->{$morphable});

        if (! isset($config[$morphable][$modelClass])) {
            return;
        }

        $attribute = $config[$morphable][$modelClass];

        $label = collect(explode('.', $attribute))
            ->reduce(function ($value, $segment) {
                return $value->{$segment};
            }, $this->model->{$morphable});

        return [
            'model_class' => $modelClass,
            'label' => $label,
        ];
    }

    private function updateKey($key)
    {
        $newKey = $this->model->getLoggable()[$key];

        $this->before[$newKey] = $this->before[$key];
        $this->after[$newKey] = $this->after[$key];

        unset($this->before[$key], $this->after[$key]);
    }

    private function before()
    {
        return collect($this->model->getOriginal())
            ->intersectByKeys($this->loggableChanges)
            ->map(function ($value, $key) {
                if ($this->after[$key] instanceof Carbon) {
                    $value = Carbon::parse($value)
                        ->format(config('enso.config.dateFormat'));
                    $this->after[$key] = $this->after[$key]
                        ->format(config('enso.config.dateFormat'));
                } else {
                    settype($value, gettype($this->after[$key]));
                }

                return $value;
            })->toArray();
    }

    private function after()
    {
        return $this->loggableChanges->toArray();
    }

    private function loggableChanges()
    {
        return collect($this->model->getDirty())
            ->intersectByKeys($this->loggableKeys()->flip());
    }

    private function loggableKeys()
    {
        return collect($this->model->getLoggable())
            ->map(function ($value, $key) {
                return is_int($key)
                    ? $value
                    : $key;
            });
    }
}
