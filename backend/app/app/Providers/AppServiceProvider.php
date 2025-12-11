<?php

namespace App\Providers;

use App\Events\PostApproved;
use App\Listeners\SendPostNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listener for PostApproved
        Event::listen(
            PostApproved::class,
            SendPostNotification::class
        );
    }
}
