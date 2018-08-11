<?php

namespace LaravelEnso\ActivityLog\app\Traits;

use LaravelEnso\ActivityLog\app\Classes\Logger;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

trait LogActivity
{
    // protected $loggableLabel = 'name';

    // protected $loggable = ['first_name' => 'first name'];

    protected static function bootLogActivity()
    {
        self::created(function ($model) {
            (new Logger($model))->onCreated();
        });

        self::updated(function ($model) {
            (new Logger($model))->onUpdated();
        });

        self::deleted(function ($model) {
            (new Logger($model))->onDeleted();
        });
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function logEvent($message, $flag = null)
    {
        (new Logger($this))->onEvent($message, $flag);
    }

    public function getLoggable()
    {
        return $this->loggable;
    }

    public function getLoggableLabel()
    {
        return $this->loggableLabel;
    }
}
