<?php

namespace App\Providers;

use App\Services\Combiner\CombinerService;
use App\Services\Combiner\Contracts\CombinerContract;
use Illuminate\Support\ServiceProvider;

class CombinerIntegrationProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() { }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CombinerContract::class, function () {
            return new CombinerService();
        });
    }
}
