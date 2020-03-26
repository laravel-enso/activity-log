<?php

namespace LaravelEnso\ActivityLog\App\Services;

use BadMethodCallException;
use Illuminate\Support\Str;
use LaravelEnso\Helpers\App\Classes\Obj;
use ReflectionClass;

class Config
{
    private const ProxiedMethods = ['alias', 'attributes', 'events', 'label'];

    private string $model;
    private Obj $config;

    public function __construct(string $model, array $config)
    {
        $this->model = $model;
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
        $this->validate($config);

        if (! isset($config['alias'])) {
            $config['alias'] = $this->defaultAlias();
        }

        return $config;
    }

    private function validate()
    {
        //TODO add config validator
    }

    private function defaultAlias()
    {
        $alias = Str::snake(
            (new ReflectionClass($this->model))->getShortName()
        );

        return str_replace('_', ' ', $alias);
    }
}
