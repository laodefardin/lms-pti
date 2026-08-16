<?php

namespace App\Providers;

use App\Services\{GamifikasiService, NilaiService, NotifikasiService};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind NotifikasiService first (no deps)
        $this->app->singleton(NotifikasiService::class);

        // GamifikasiService depends on NotifikasiService
        $this->app->singleton(GamifikasiService::class, function ($app) {
            return new GamifikasiService($app->make(NotifikasiService::class));
        });

        // NilaiService (independent)
        $this->app->singleton(NilaiService::class);
    }

    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
