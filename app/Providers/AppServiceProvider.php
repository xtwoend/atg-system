<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use App\Console\Commands\MQTTListener;
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
        Paginator::useBootstrapFive();
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                MQTTListener::class,
            ]);
        }
    }
}
