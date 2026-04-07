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
    #[\Override]
    public function register(): void
    {
        $this->app->singleton('cart', fn($app) => new CartService);

        $this->app->bind(fn($app): \App\Services\CartService => $app->make('cart'));
    }

    /** Bootstrap services. */
    public function boot(): void {}

    /** Get the services provided by the provider. */
    #[\Override]
    public function provides(): array
    {
        return ['cart', CartService::class];
    }
}
