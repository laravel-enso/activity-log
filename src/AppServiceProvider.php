<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/databa s e/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes / api.php');
    }

    public function register()
    {
        //
    }
}
