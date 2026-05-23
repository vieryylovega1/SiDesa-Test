<?php

namespace App\Providers;

use App\Models\Resident;
use App\Observers\ResidentObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Paginator::useBootstrapFive();
        Resident::observe(ResidentObserver::class);
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
