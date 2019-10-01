<?php

namespace LaravelEnso\ActivityLog\app\Services;

use ReflectionClass;
use BadMethodCallException;
use Illuminate\Support\Str;
use LaravelEnso\Helpers\app\Classes\Obj;

class Config
{
    private const ProxiedMethods = ['alias', 'attributes', 'events', 'label'];

    private $model;
    private $config;

    public function __construct(string $model, array $config)
    {
        $this->model = $model;
        $this->config = new Obj($this->parse($config));
    }

    public function __call($method, $args)
    {
        if (collect(self::ProxiedMethods)->contains($method)) {
            return $this->config->get($method);
        }

        throw new BadMethodCallException('Method '.static::class.'::'.$method.'() not found');
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

        return  $config;
    }

    private function validate($config)
    {
        //TODO add config validator
    }

    private function defaultAlias()
    {
        return str_replace(
            '_', ' ', Str::snake((new ReflectionClass($this->model))->getShortName())
        );
    }
}
