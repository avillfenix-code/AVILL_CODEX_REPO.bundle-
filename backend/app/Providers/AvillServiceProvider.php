<?php

namespace App\Providers;

use App\Services\AvillFareService;
use Illuminate\Support\ServiceProvider;

class AvillServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AvillFareService::class, function ($app) {
            return new AvillFareService();
        });
    }

    public function boot()
    {
        //
    }
}
