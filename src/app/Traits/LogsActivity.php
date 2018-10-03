<?php

namespace LaravelEnso\ActivityLog\app\Traits;

use LaravelEnso\ActivityLog\app\Classes\Logger;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

trait LogsActivity
{
    // protected $loggable = ['first_name' => 'first name']; -> optional;

    // protected $loggableLabel = 'name'; -> optional, default = 'name';

    // protected $loggableMorph = ['morphable' => [Model::class => 'attribute']] -> optional

    protected static function bootLogsActivity()
    {
        self::created(function ($model) {
            if (auth()->user()) {
                (new Logger($model))->onCreated();
            }
        });

        self::updated(function ($model) {
            if (auth()->user()) {
                (new Logger($model))->onUpdated();
            }
        });

        self::deleted(function ($model) {
            if (auth()->user()) {
                (new Logger($model))->onDeleted();
            }
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
        return $this->loggable ?? [];
    }

    public function getLoggableLabel()
    {
        return $this->loggableLabel ?? 'name';
    }

    public function getLoggableMorph()
    {
        return $this->loggableMorph;
    }
}
