<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OpenFoodFactsService;
use OpenFoodFacts\Api as OpenFoodFactsApi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenFoodFactsService::class, function ($app) {
            return new OpenFoodFactsService($app->make('log'));
        });

        $this->app->singleton('openfoodfacts', function ($app) {
            return $app->make(OpenFoodFactsService::class);
        });

        $this->app->singleton(OpenFoodFactsApi::class, function ($app) {
            return $app->make('openfoodfacts')->default();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
