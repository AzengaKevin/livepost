<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaginationServiceProvider extends ServiceProvider
{
    public const PER_PAGE = 16;
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
