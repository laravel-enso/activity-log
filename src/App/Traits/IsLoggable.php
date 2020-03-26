<?php

namespace LaravelEnso\ActivityLog\App\Traits;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\App\Facades\Logger;
use LaravelEnso\ActivityLog\App\Services\Config;

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
