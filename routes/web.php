<?php

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');

if (app()->environment('local')) {

    Route::get('/test', fn() => (new WelcomeMail(User::factory()->make()))->render());
    
}
