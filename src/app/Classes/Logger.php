<?php

namespace LaravelEnso\ActivityLog\app\Classes;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

class Logger
{
    private $model;
    private $loggable;
    private $loggableChanges;
    private $before = null;
    private $after = null;

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
        $this->parse();
        $this->log(Events::Updated);
    }

    public function onDeleted()
    {
        $this->log(Events::Deleted);
    }

    public function onEvent($message, $icon)
    {
        $this->log(Events::Custom, $message, $icon);
    }

    private function log($event, $message = null, $icon = null)
    {
        ActivityLog::create([
            'model_class' => get_class($this->model),
            'model_id' => $this->model->getKey(),
            'event' => $event,
            'meta' => [
                'label' => $this->model->{$this->model->getLoggableLabel()},
                'before' => $this->before,
                'after' => $this->after,
                'message' => $message,
                'icon' => $icon
            ]
        ]);
    }

    private function parse()
    {
        collect($this->loggableChanges)
            ->each(function ($value, $key) {
                if (!isset($this->model->getLoggable()[$key])) {
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
    }

    private function readRelation($key)
    {
        $this->before[$key] = $this->model
            ->getLoggable()[$key][0]::find($this->before[$key])
            ->{$this->model->getLoggable()[$key][1]};

        $this->after[$key] = $this->model
            ->getLoggable()[$key][0]::find($this->after[$key])
            ->{$this->model->getLoggable()[$key][1]};
    }

    private function readEnum($key)
    {
        $this->before[$key] = $this->model
            ->getLoggable()[$key]::get($this->before[$key]);

        $this->after[$key] = $this->model
            ->getLoggable()[$key]::get($this->after[$key]);
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
                settype($value, gettype($this->after[$key]));

                return $value;
            })
            ->toArray();
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
                return is_integer($key)
                    ? $value
                    : $key;
            });
    }
}
