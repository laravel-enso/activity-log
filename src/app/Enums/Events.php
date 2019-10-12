<?php

namespace LaravelEnso\ActivityLog\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class Events extends Enum
{
    const Created = 1;
    const Updated = 2;
    const Deleted = 3;
    const Activation = 4;
}
