<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;

/**
 * Cart Service Provider
 *
 * Registers the CartService and provides cart instances management
 */
class CartServiceProvider extends ServiceProvider
{
    /** Register services. */
    public function register(): void
    {
        $this->app->singleton('cart', function ($app) {
            return new CartService();
        });

        $this->app->bind(CartService::class, function ($app) {
            return $app->make('cart');
        });
    }

    /** Bootstrap services. */
    public function boot(): void
    {

    }

    /** Get the services provided by the provider. */
    public function provides(): array
    {
        return ['cart', CartService::class];
    }
}
