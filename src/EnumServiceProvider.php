<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\Facades\App;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->register[] = App::make(Events::class)->all();

        parent::boot();
    }
}
