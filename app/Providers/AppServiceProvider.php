<?php

namespace App\Providers;

use App\Contracts\PaymentServiceInterface;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Services\Payment\PaymentService;
use App\Services\PlaceToPayService;
use App\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);

        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);

        $this->app->bind(PlaceToPayServiceInterface::class, PlaceToPayService::class);
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
