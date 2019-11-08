<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class Events extends Enum
{
    const Created = 1;
    const Updated = 2;
    const Deleted = 3;
    const UpdatedActiveState = 4;

    protected static function attributes()
    {
        return [
            static::Created => 'Created',
            static::Updated => 'Updated',
            static::Deleted => 'Deleted',
            static::UpdatedActiveState => 'Updated Active State',
        ];
    }
}
