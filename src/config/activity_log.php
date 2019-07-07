<?php

use LaravelEnso\ActivityLog\app\Enums\Events;

return [
    'custom_events' => [
        Events::Created => 'created',
        Events::Updated => 'updated',
        Events::Deleted => 'deleted',
        Events::Custom => 'custom',
    ],
];
