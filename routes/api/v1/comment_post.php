<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostCommentController;

Route::middleware('auth:sanctum')
    ->as('posts.comments.')
    ->group(function () {
        Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])
            ->name('index');
    });