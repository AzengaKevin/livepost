<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CommentController;

Route::middleware('auth:sanctum')
    ->as('comments.')
    ->group(function () {
        Route::get('/comments', [CommentController::class, 'index'])->name('index');
        Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('show');
        Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });
