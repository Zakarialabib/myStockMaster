<?php

declare(strict_types=1);

namespace App\Support\Cart;

use App\Support\Cart\Contracts\CartDriver;
use App\Support\Cart\Drivers\SessionDriver;
use Illuminate\Support\Manager;

final class CartManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('cart.storage', 'session');
    }

    public function createSessionDriver(): CartDriver
    {
        return new SessionDriver();
    }

    public function get(string $instance): \Illuminate\Support\Collection
    {
        return $this->driver()->get($instance);
    }

    public function put(string $instance, \Illuminate\Support\Collection $content): void
    {
        $this->driver()->put($instance, $content);
    }

    public function forget(string $instance): void
    {
        $this->driver()->forget($instance);
    }
}
