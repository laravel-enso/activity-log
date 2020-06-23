<?php

namespace LaravelEnso\ActivityLog\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\ActivityLog\Http\Responses\Timeline;

class Index extends Controller
{
    public function __invoke(Timeline $timeline)
    {
        return $timeline;
    }
}
