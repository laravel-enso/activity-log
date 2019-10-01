<?php

namespace LaravelEnso\ActivityLog\App\Events;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Enums\app\Services\Enum;
use LaravelEnso\Helpers\app\Classes\Obj;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Facades\Logger;
use LaravelEnso\ActivityLog\app\Traits\IsLoggable;
use LaravelEnso\ActivityLog\App\Contracts\Loggable;
use LaravelEnso\ActivityLog\App\Contracts\ProvidesAttributes;

class Updated implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    private $model;
    private $loggableChanges;
    private $attributes;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->attributes = Logger::config($model)->attributes();
        $this->loggableChanges = $this->loggableChanges();
    }

    public function type(): int
    {
        return Events::Updated;
    }

    public function message(): string
    {
        $message = ':user updated :model :label';

        $index = 0;

        $changes = $this->loggableChanges->reduce(function ($message) use (&$index) {
            return $message->push(":attribute{++$index} was changed from :from{$index} to :to{$index}");
        }, collect())->implode(', ');

        return $changes ? "{$message} with the following changes: {$changes}" : $message;
    }

    public function icon(): string
    {
        return 'pencil-alt';
    }

    public function attributes(): array
    {
        $index = 0;

        return $this->loggableChanges->reduce(function ($attributes, $attribute) use (&$index) {
            $index++;

            return $attributes->put(
                "attribute{$index}", $this->attribute($attribute)
            )->put(
                "from{$index}", $this->parse($attribute, $this->model->getOriginal($attribute))
            )->put(
                "to{$index}", $this->parse($attribute, $this->model->{$attribute})
            );
        }, collect())->toArray();
    }

    private function attribute($attribute)
    {
        return str_replace(['_id', '_'], ['', ' '], $attribute);
    }

    private function parse($attribute, $value)
    {
        if (! isset($this->attributes[$attribute])) {
            return $value;
        }
        
        if ($this->attributes[$attribute] instanceof Obj) {
            return $this->readRelation($this->attributes[$attribute], $value);
        }

        if (class_exists($this->attributes[$attribute])
            && (new ReflectionClass($this->attributes[$attribute]))
                ->isSubclassOf(Enum::class)) {
            return $this->readEnum($this->attributes[$attribute], $value);
        }
    }

    private function readRelation($relation, $value)
    {
        $class = key($relation->toArray());
        $attribute = $relation->get($class);

        return $class::find($value)->{$attribute};
    }

    private function readEnum($enum, $value)
    {
        value($enum)::localisation(false);

        return value($enum)::get($value);
    }

    private function loggableChanges()
    {
        return collect($this->model->getDirty())
            ->intersectByKeys($this->loggableAttributes()->flip())
            ->keys();
    }

    private function loggableAttributes()
    {
        return collect($this->attributes)
            ->map(function ($value, $key) {
                return is_int($key) ? $value : $key;
            });
    }
}
