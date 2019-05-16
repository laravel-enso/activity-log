<?php

namespace LaravelEnso\ActivityLog\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\ActivityLog\app\Http\Responses\Feed;

class Index extends Controller
{
    public function __invoke()
    {
        return new Feed;
    }
}
