<?php

namespace LaravelEnso\ActivityLog\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\ActivityLog\app\Http\Responses\Feed;

class ActivityLogController extends Controller
{
    public function index()
    {
        return new Feed;
    }
}
