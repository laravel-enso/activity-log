<?php

namespace LaravelEnso\ActivityLog\Traits;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\Facades\Logger;
use LaravelEnso\ActivityLog\Services\Config;

trait IsLoggable
{
    public function model(): Model
    {
        return $this->model;
    }

    public function config(): Config
    {
        return Logger::config($this->model);
    }
}
