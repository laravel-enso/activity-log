<?php

namespace LaravelEnso\ActivityLog\app\Traits;

use LaravelEnso\ActivityLog\app\Classes\Logger;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

trait LogActivity
{
    // protected $loggable = ['first_name' => 'first name']; -> optional;

    // protected $loggableLabel = 'name'; -> optional;

    // protected $loggableMorph = ['morphable' => [Model::class => 'attribute']] -> optional

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
