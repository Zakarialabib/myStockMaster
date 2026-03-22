<?php

declare(strict_types=1);

namespace App\Support\Cart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection get(string $instance)
 * @method static void put(string $instance, \Illuminate\Support\Collection $content)
 * @method static void forget(string $instance)
 * @method static mixed driver(string|null $driver = null)
 */
final class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Support\Cart\CartManager::class;
    }
}
