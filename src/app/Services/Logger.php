<?php

namespace LaravelEnso\ActivityLog\app\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use LaravelEnso\ActivityLog\app\Enums\Observers;

class Logger
{
    private Collection $models;
    private Collection $ignored;

    public function __construct()
    {
        $this->models = new Collection();
        $this->ignored = new Collection();
    }

    public function register($models)
    {
        (new Collection($models))
            ->each(fn ($config, $model) => $this->models
                ->put($model, new Config($model, $config))
            );
    }

    public function observe()
    {
        $this->monitored()->each(fn ($config, $model) => $config->events()
            ->intersect(App::make(Observers::class)::keys())
            ->each(fn ($event) => $model::observe(
                App::make(Observers::class)::get($event))
            )
        );
    }

    public function config($model)
    {
        return $this->models->get(
            $model instanceof Model ? get_class($model) : $model
        );
    }

    public function remove($models)
    {
        (new Collection($models))->each(fn ($model) => $this->models->forget($model));
    }

    public function all()
    {
        return $this->models;
    }

    public function monitored()
    {
        return $this->models->reject(fn ($config) => $this->ignored
            ->contains($config->alias())
        );
    }

    public function ignore($model)
    {
        if (! $this->ignored->contains($model['alias'])) {
            $this->ignored->push($model['alias']);
        }
    }

    public function unignore($model)
    {
        if ($this->ignored->contains($model['alias'])) {
            $this->ignored->forget($model['alias']);
        }
    }
}
