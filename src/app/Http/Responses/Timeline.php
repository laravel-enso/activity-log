<?php

namespace LaravelEnso\ActivityLog\app\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;
use LaravelEnso\TrackWho\app\Http\Resources\TrackWho;

class Timeline implements Responsable
{
    private const Chunk = 50;

    private $timeline;

    public function toResponse($request)
    {
        $this->timeline = $this->timeline($request);

        return $this->days()->map(function ($day) {
            return [
                'date' => $day,
                'entries' => $this->dayEntries($day)->map(function ($entry) {
                    return $this->resource($entry);
                }),
            ];
        }, []);
    }

    public function timeline($request)
    {
        $filters = json_decode($request->get('filters'));

        return ActivityLog::latest()
            ->with('createdBy.person', 'createdBy.avatar')
            ->skip($request->get('offset'))
            ->between($filters->interval->min, $filters->interval->max)
            ->forUsers($filters->userIds)
            ->forEvents($filters->events)
            ->forRoles($filters->roleIds)
            ->take(self::Chunk)
            ->get();
    }

    private function resource($entry)
    {
        return [
            'id' => $entry->id,
            'meta' => $entry->meta,
            'time' => $entry->created_at->format('H:i A'),
            'owner' => new TrackWho($entry->createdBy),
        ];
    }

    private function dayEntries($day)
    {
        return $this->timeline->filter(function ($entry) use ($day) {
            return $entry->created_at->format('Y-m-d') === $day;
        })->values();
    }

    private function days()
    {
        return $this->timeline->map(function ($entry) {
            return $entry->created_at->format('Y-m-d');
        })->unique()->values();
    }
}
