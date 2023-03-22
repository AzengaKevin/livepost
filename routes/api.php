<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Version 1 Routes
Route::prefix('v1')
    ->group(function(){

        // Users API routes
        require __DIR__ . '/api/v1/users.php';

        // Posts API routes
        require __DIR__ . '/api/v1/posts.php';

        // Comments API routes
        require __DIR__ . '/api/v1/comments.php';

    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
