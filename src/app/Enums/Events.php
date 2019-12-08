<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class Events extends Enum
{
    public const Created = 1;
    public const Updated = 2;
    public const Deleted = 3;
    public const UpdatedActiveState = 4;

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
