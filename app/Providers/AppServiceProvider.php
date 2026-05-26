<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\ReadingLog;
use App\Observers\ReadingLogObserver;

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
        // Usar Tailwind CSS para los links de paginación de Laravel
        Paginator::useTailwind();

        ReadingLog::observe(ReadingLogObserver::class);
    }
}
