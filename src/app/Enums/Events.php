<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;

class Events extends Enum
{
    const Created = 1;
    const Updated = 2;
    const Deleted = 3;
    const Custom = 4;

    protected static function attributes()
    {
        return config('enso.activity_log.events', []);
    }

    public static function isCustom($eventType)
    {
        return ! in_array($eventType, [self::Created, self::Updated, self::Deleted]);
    }

}
