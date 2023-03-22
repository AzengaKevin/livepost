<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Version 1 Routes
Route::prefix('v1')
    ->group(function () {
        \App\Helpers\RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
