<?php

namespace LaravelEnso\ActivityLog\app\Traits;

use Illuminate\Support\Facades\Auth;
use LaravelEnso\ActivityLog\app\Services\Logger;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

trait LogsActivity
{
    // protected $loggable = ['first_name' => 'first name', 'group_id' => [UserGroup::class => 'name']]; -> optional
    // protected $loggableLabel = 'name'; -> optional, default = 'name'
    // protected $loggableRelation = ['relation' => 'name']' -> optional'
    // protected $loggableMorph = ['morphable' => [Model::class => 'attribute']] -> optional
    // protected $loggedEvents = ['created'] // optional, default ['created', 'updated', 'deleted'];

    protected static function bootLogsActivity()
    {
        self::created(function ($model) {
            if (Auth::check()
                && collect($model->getLoggedEvents())->contains('created')) {
                (new Logger($model))->onCreated();
            }
        });

        self::updated(function ($model) {
            if (Auth::check()
                && collect($model->getLoggedEvents())->contains('updated')) {
                (new Logger($model))->onUpdated();
            }
        });

        self::deleted(function ($model) {
            if (Auth::check()
                && collect($model->getLoggedEvents())->contains('deleted')) {
                (new Logger($model))->onDeleted();
            }
        });
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function logEvent($message, $icon = null)
    {
        (new Logger($this))->onEvent($message, $icon);
    }

    public function getLoggable()
    {
        return $this->loggable ?? [];
    }

    public function getLoggableLabel()
    {
        return $this->loggableLabel ?? 'name';
    }

    public function getLoggableRelation()
    {
        return $this->loggableRelation ?? null;
    }

    public function getLoggableMorph()
    {
        return $this->loggableMorph ?? null;
    }

    public function getLoggedEvents()
    {
        return $this->loggedEvents
            ?? ['created', 'updated', 'deleted'];
    }
}
