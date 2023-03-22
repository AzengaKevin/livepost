<?php

use Illuminate\Support\Facades\Route;

// Version 1 Routes
Route::prefix('v1')
    ->as('v1.')
    ->group(function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
    });
