<?php

namespace LaravelEnso\ActivityLog\Services;

use BadMethodCallException;
use Illuminate\Support\Str;
use LaravelEnso\Helpers\Services\Obj;
use ReflectionClass;

class Config
{
    private const ProxiedMethods = ['alias', 'attributes', 'events', 'label'];

    private Obj $config;

    public function __construct(private string $model, array $config)
    {
        $this->config = new Obj($this->parse($config));
    }

    public function __call($method, $args)
    {
        if (in_array($method, self::ProxiedMethods)) {
            return $this->config->get($method);
        }

        $class = static::class;

        throw new BadMethodCallException("Method {$class}::{$method}() not found");
    }

    public function has($attribute)
    {
        return $this->config->has($attribute);
    }

    private function parse($config)
    {
        if (! isset($config['alias'])) {
            $config['alias'] = $this->defaultAlias();
        }

        return $config;
    }

    private function defaultAlias()
    {
        $alias = Str::snake(
            (new ReflectionClass($this->model))->getShortName()
        );

        return str_replace('_', ' ', $alias);
    }
}
