<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\ActivityLog\App\Services\Logger;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        'logger' => Logger::class,
    ];

    public function boot()
    {
        $this->load()
            ->publish();
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/../stubs/LoggerServiceProvider.stub' => app_path(
                'Providers/LoggerServiceProvider.php'
            ),
        ], 'activity-log-provider');
    }
}
