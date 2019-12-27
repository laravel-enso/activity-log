<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\ActivityLog\app\Http\Controllers')
    ->prefix('api/core/activityLogs')
    ->as('core.activityLogs.')
    ->group(fn () => Route::get('', 'Index')->name('index'));
