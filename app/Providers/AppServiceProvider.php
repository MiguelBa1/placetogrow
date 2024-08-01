<?php

namespace App\Providers;

use App\Factories\PaymentDataProviderFactory;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentDataProviderFactory::class, function () {
            return new PaymentDataProviderFactory();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('locale', function () {
            return session('locale', config('app.locale'));
        });
    }
}
