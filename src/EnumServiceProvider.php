<?php

namespace LaravelEnso\ActivityLog;

use Illuminate\Support\Facades\App;
use LaravelEnso\ActivityLog\App\Enums\Events;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->register['loggableEvents'] = App::make(Events::class);

        parent::boot();
    }
}
