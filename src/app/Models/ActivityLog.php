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

    protected $dates = ['created_at', 'updated_at'];

    public function scopeBetween($query, $startDate, $endDate)
    {
        $query->when($startDate, function ($query) use ($startDate) {
            $query->where('created_at', '>', Carbon::createFromFormat(
                config('enso.config.dateFormat'),
                $startDate
            )->format('Y-m-d'));
        })->when($endDate, function ($query) use ($endDate) {
            $query->where('created_at', '<', Carbon::createFromFormat(
                config('enso.config.dateFormat'),
                $endDate
            )->format('Y-m-d'));
        });
    }

    public function scopeBelongingTo($query, $userIds)
    {
        $query->when(count($userIds), function ($query) use ($userIds) {
            $query->whereIn('created_by', $userIds);
        });
    }

    public function scopeForEvents($query, $events)
    {
        $query->when(count($events), function ($query) use ($events) {
            $query->whereIn('event', $events);
        });
    }

    public function scopeForRoles($query, $roleIds)
    {
        $query->when(count($roleIds), function ($query) use ($roleIds) {
            $query->whereHas('createdBy', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            });
        });
    }
}
