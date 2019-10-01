<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\ActivityLog\app\Facades\Logger;

class LoggerServiceProvider extends ServiceProvider
{
    public $register = [];

    public function boot()
    {
        Logger::register($this->register);
    }
}
