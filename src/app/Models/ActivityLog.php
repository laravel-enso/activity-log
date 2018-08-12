<?php

namespace LaravelEnso\ActivityLog\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class ActivityLog extends Model
{
    use CreatedBy;

    protected $fillable = [
        'model_class', 'model_id', 'model_identifier', 'event', 'meta',
    ];

    protected $casts = ['meta' => 'object'];
}
