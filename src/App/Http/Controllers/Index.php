<?php

namespace LaravelEnso\ActivityLog\App\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\ActivityLog\App\Http\Responses\Timeline;

class Index extends Controller
{
    public function __invoke(Timeline $timeline)
    {
        return $timeline;
    }
}
