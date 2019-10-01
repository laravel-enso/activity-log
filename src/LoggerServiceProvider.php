<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\ActivityLog\app\Facades\Logger;

class LoggerServiceProvider extends ServiceProvider
{
    public $register = [];

    public function boot()
    {
        if (! app()->environment('testing')) {
            Logger::register($this->register);
        }
    }
}
