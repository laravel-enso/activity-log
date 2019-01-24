<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\ActivityLog\app\Http\Controllers')
    ->prefix('core')->as('core.')
    ->group(function () {
        Route::resource('activityLogs', 'ActivityLogController', ['only' => 'index']);
    });
