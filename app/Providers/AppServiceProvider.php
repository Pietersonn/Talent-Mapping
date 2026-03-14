<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator; // Pastikan ini ada
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // View Composer (Biarkan kode ini, sudah benar)
        View::composer(
            ['admin.layouts.partials.sidebar', 'admin.layouts.partials.navbar' ,'admin.layouts.app.blade.php'],
            function ($view) {
                $view->with('user', Auth::user());
            }
        );

        // Konfigurasi URL untuk Production (Biarkan jika sudah benar)
        if (config('app.env') === 'production') {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
}
