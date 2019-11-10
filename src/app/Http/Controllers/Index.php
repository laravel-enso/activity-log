<?php

namespace LaravelEnso\ActivityLog\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\ActivityLog\app\Http\Responses\Timeline;

class Index extends Controller
{
    public function __invoke(Timeline $timeline)
    {
        return $timeline;
    }
}
