<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\ActivityLog\Http\Controllers\Index;

Route::get('api/core/activityLogs', Index::class)
    ->name('core.activityLogs.index')
    ->middleware('api', 'auth', 'core');
