<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\ActivityLog\Http\Controllers\Index;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/activityLogs')
    ->as('core.activityLogs.')
    ->group(fn () => Route::get('', Index::class)->name('index'));
