<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Cart Facade
 *
 * @method static \App\Services\CartService      instance(string $name = 'default')
 * @method static \App\Services\CartService      add(mixed $id, string $name = null, int $quantity = 1, float $price = 0.0, array $options = [], array $attributes = [])
 * @method static \App\Services\CartService      update(string $rowId, mixed $qty)
 * @method static \App\Services\CartService      remove(string $rowId)
 * @method static \App\Services\CartService      destroy()
 * @method static \Illuminate\Support\Collection content()
 * @method static int                            count()
 * @method static float                          total()
 * @method static float                          subtotal()
 * @method static float                          tax()
 * @method static float                          discount()
 * @method static \App\Services\CartService      setTax(float $taxRate)
 * @method static \App\Services\CartService      addCondition(array $condition)
 * @method static \App\Services\CartService      removeCondition(string $name)
 * @method static array                          getConditions()
 * @method static \App\Services\CartService      clearConditions()
 * @method static mixed                          get(string $rowId)
 * @method static \App\Services\CartService      associate(string $model)
 * @method static bool                           isEmpty()
 * @method static \App\Services\CartService      clear()
 * @method static \App\Services\CartService      store(string $identifier)
 * @method static \App\Services\CartService      restore(string $identifier)
 * @method static \App\Services\CartService      erase(string $identifier)
 * @method static array                          getSummary()
 *
 * @see \App\Services\CartService
 */
class Cart extends Facade
{
    /** Get the registered name of the component. */
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
