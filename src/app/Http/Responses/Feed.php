<?php

namespace LaravelEnso\ActivityLog\app\Http\Responses;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use LaravelEnso\ActivityLog\app\Enums\Events;
use LaravelEnso\ActivityLog\app\Models\ActivityLog;

class Feed implements Responsable
{
    private const Chunk = 30;

    private $feed;

    public function toResponse($request)
    {
        $this->setFeed($request);

        return $this->feed();
    }

    public function setFeed($request)
    {
        $filters = json_decode($request->get('filters'));

        $query = ActivityLog::with('createdBy')
            ->latest()
            ->skip($request->get('offset'))
            ->take(self::Chunk);

        if ($filters->intervals->min) {
            $query->where('created_at', '>', Carbon::parse($filters->intervals->min));
        }

        if ($filters->intervals->max) {
            $query->where('created_at', '<', Carbon::parse($filters->intervals->max));
        }

        if (count($filters->user_ids)) {
            $query->whereIn('created_by', $filters->user_ids);
        }

        if (count($filters->events)) {
            $query->whereIn('event', $filters->events);
        }

        if (count($filters->role_ids)) {
            $query->whereHas('createdBy', function ($query) use ($filters) {
                $query->whereIn('role_id', $filters->role_ids);
            });
        }

        $this->feed = $query->get();
    }

    private function feed()
    {
        $days = $this->feed->map(function ($item) {
            return $item->created_at->format('Y-m-d');
        })->unique()
            ->values();

        return $days->map(function ($day) {
            return [
                'date' => $day,
                'list' => $this->feed->filter(function ($item) use ($day) {
                    return $item['created_at']->format('Y-m-d') === $day;
                })->values()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'model' => $this->model($item->model_class),
                        'action' => [
                            'type' => $item->event,
                            'label' => lcfirst(Events::get($item->event)),
                            'icon' => $item->meta->icon,
                        ],
                        'label' => $item->meta->label,
                        'changes' => $this->changes($item),
                        'message' => $item->event === Events::Custom
                            ? $item->meta->message
                            : null,
                        'time' => $item->created_at->format('H:i A'),
                        'author' => [
                            'name' => $item->createdBy->fullName,
                            'avatarId' => $item->createdBy->avatarId
                        ],
                        'morphable' => $this->morphable($item),
                    ];
                }),
            ];
        }, []);
    }

    private function model($class)
    {
        $model = collect(
            explode('\\', $class)
        )->last();

        return str_replace('_', ' ', snake_case($model));
    }

    private function changes($item)
    {
        return $item->event !== Events::Updated
            ? []
            : collect($item->meta->before)
                ->keys()
                ->map(function ($key) use ($item) {
                    return [
                        'attribute' => $this->attribute($key),
                        'before' => $item->meta->before->{$key},
                        'after' => $item->meta->after->{$key},
                    ];
                })->toArray();
    }

    private function attribute($key)
    {
        return str_replace(['_id', '_'], ['', ' '], $key);
    }

    private function morphable($item)
    {
        return $item->meta->morphable
            ? [
                    'model' => $this->model($item->meta->morphable->model_class),
                    'label' => $item->meta->morphable->label
            ]
            : null;
    }
}
