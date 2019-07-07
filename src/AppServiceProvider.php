<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->mergeConfigFrom(__DIR__.'/config/activity_log.php', 'enso.activity_log');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
