<?php

namespace LaravelEnso\ActivityLog\app\Traits;

trait ActivationLog
{
    protected static function bootActivationLog()
    {
        self::updating(function ($model) {
            if (collect($model->getDirty())->has($model->isActiveAttribute())) {
                $model->fireModelEvent('activation', false);
            }
        });
    }

    public function isActive()
    {
        return $this->{$this->isActiveAttribute()};
    }

    protected function initializeActivationLog()
    {
        if (! in_array('activation', $this->observables)) {
            $this->observables[] = 'activation';
        }
    }

    private function isActiveAttribute()
    {
        return property_exists($this, 'isActiveAttribute')
            ? $this->isActiveAttribute
            : 'is_active';
    }
}
