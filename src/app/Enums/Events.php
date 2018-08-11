<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;

class Events extends Enum
{
    const Created = 1;
    const Updated = 2;
    const Deleted = 3;
    const Custom = 4;
}
