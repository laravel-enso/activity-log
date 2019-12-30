<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\ActivityLog\App\Http\Controllers')
    ->prefix('api/core/activityLogs')
    ->as('core.activityLogs.')
    ->group(fn () => Route::get('', 'Index')->name('index'));
