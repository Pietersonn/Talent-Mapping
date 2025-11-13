<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

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
        // paksa root url sesuai APP_URL dan paksa scheme https di production
        if (config('app.env') === 'production') {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
        Paginator::useBootstrapFour();
    }
    // Di app/Providers/EventServiceProvider.php
    protected $listen = [
        \App\Events\TestCompleted::class => [
            \App\Listeners\GenerateTestResult::class,
        ],
    ];
}
