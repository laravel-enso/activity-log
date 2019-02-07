<?php

namespace LaravelEnso\ActivityLog\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class ActivityLog extends Model
{
    use CreatedBy;

    protected $fillable = [
        'model_class', 'model_id', 'model_identifier', 'event', 'meta',
    ];

    protected $casts = ['meta' => 'object'];

    public function scopeBetween($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->where('created_at', '>', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->where('created_at', '<', Carbon::parse($endDate));
        }
    }

    public function scopeBelongingTo($query, $userIds)
    {
        if (count($userIds)) {
            $query->whereIn('created_by', $userIds);
        }
    }

    public function scopeForEvents($query, $events)
    {
        if (count($events)) {
            $query->whereIn('event', $events);
        }
    }

    public function scopeForRoles($query, $roleIds)
    {
        if (count($roleIds)) {
            $query->whereHas('createdBy', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            });
        }
    }
}
