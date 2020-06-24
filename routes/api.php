<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->namespace('LaravelEnso\ActivityLog\Http\Controllers')
    ->prefix('api/core/activityLogs')
    ->as('core.activityLogs.')
    ->group(fn () => Route::get('', 'Index')->name('index'));
