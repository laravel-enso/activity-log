<?php

namespace LaravelEnso\ActivityLog\app\Traits;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\app\Facades\Logger;
use LaravelEnso\ActivityLog\app\Services\Config;

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
