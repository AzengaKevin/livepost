<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

Route::middleware('auth:sanctum')
    ->as('users.')
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::post('/users', [UserController::class, 'store'])->name('store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('show');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
