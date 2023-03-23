<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController;

Route::middleware('auth:sanctum')
    ->as('posts.')
    ->group(function () {

        Route::get('/posts', [PostController::class, 'index'])
            ->name('index')
            ->withoutMiddleware('auth:sanctum');

        Route::post('/posts', [PostController::class, 'store'])
            ->name('store');

        Route::get('/posts/{post}', [PostController::class, 'show'])
            ->whereNumber(['post'])
            ->name('show')
            ->withoutMiddleware('auth:sanctum');

        Route::patch('/posts/{post}', [PostController::class, 'update'])
            ->whereNumber(['post'])
            ->name('update');

        Route::delete('/posts/{post}', [PostController::class, 'destroy'])
            ->whereNumber(['post'])
            ->name('destroy');
    });
