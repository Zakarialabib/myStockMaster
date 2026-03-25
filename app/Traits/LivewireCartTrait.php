<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\CartService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

trait LivewireCartTrait
{
    protected ?CartService $cart = null;

    public string $cartInstance = 'default';

    public array $cartConfig = [];

    /** Initialize cart for Livewire component */
    public function initializeCart(?string $instance = null, array $config = []): void
    {
        $this->cartInstance = $instance ?? $this->cartInstance;
        $this->cartConfig = array_merge($this->cartConfig, $config);

        $this->cart = App::make(CartService::class, ['instanceName' => $this->cartInstance]);
        $this->cart->configureForLivewire($this->getComponentType());
        $this->applyCartConfiguration();
    }

    /** Boot hook - runs before mount */
    public function bootLivewireCartTrait(): void
    {
        // Do not initialize cart here - public properties are not yet available
    }

    /** Mount hook - runs once on component initialization */
    public function mountLivewireCartTrait(): void
    {
        $this->initializeCart($this->cartInstance ?: $this->getComponentType());
    }

    /** Hydrate hook - runs on every request after component is hydrated */
    public function hydrateLivewireCartTrait(): void
    {
        $this->initializeCart($this->cartInstance ?: $this->getComponentType());
        $this->syncCartState();
    }

    /** Dehydrate hook - runs before component is sent to browser */
    public function dehydrateLivewireCartTrait(): void
    {
        $this->persistCartState();
    }

    /** Get component type for cart configuration */
    protected function getComponentType(): string
    {
        $className = class_basename(static::class);

        if (str_contains(strtolower($className), 'pos')) {
            return 'pos';
        }

        if (str_contains(strtolower($className), 'sale')) {
            return 'sales';
        }

        if (str_contains(strtolower($className), 'purchase')) {
            return 'purchases';
        }

        if (str_contains(strtolower($className), 'quotation') || str_contains(strtolower($className), 'quote')) {
            return 'quotation';
        }

        return 'default';
    }

    /** Apply custom cart configuration from component */
    protected function applyCartConfiguration(): void
    {
        if (isset($this->cartConfig['primary_storage'])) {
            $this->cart->setPrimaryStorage($this->cartConfig['primary_storage']);
        }

        if (isset($this->cartConfig['secondary_storage'])) {
            $this->cart->setSecondaryStorage($this->cartConfig['secondary_storage']);
        }

        if (isset($this->cartConfig['hybrid_storage'])) {
            $this->cart->setHybridStorage($this->cartConfig['hybrid_storage']);
        }

        if (isset($this->cartConfig['cache_duration'])) {
            $this->cart->setCacheDuration($this->cartConfig['cache_duration']);
        }
    }

    /** Sync component state with cart - override in component if needed */
    protected function syncCartState(): void {}

    /** Persist cart state - override in component if needed */
    protected function persistCartState(): void {}

    #[Computed]
    public function cartContent(): Collection
    {
        return $this->getCart()->content();
    }

    #[Computed]
    public function cartCount(): int
    {
        return $this->getCart()->count();
    }

    #[Computed]
    public function cartTotal(): float
    {
        return $this->getCart()->total();
    }

    #[Computed]
    public function cartSubtotal(): float
    {
        return $this->getCart()->subtotal();
    }

    #[Computed]
    public function cartTax(): float
    {
        return $this->getCart()->tax();
    }

    #[Computed]
    public function cartDiscount(): float
    {
        return $this->getCart()->discount();
    }

    #[Computed]
    public function cartIsEmpty(): bool
    {
        return $this->getCart()->isEmpty();
    }

    /** Get or create cart instance */
    protected function getCart(): CartService
    {
        if ($this->cart === null) {
            $this->initializeCart($this->cartInstance ?: $this->getComponentType());
        }

        return $this->cart;
    }

    /** Add item to cart */
    public function addToCart(array $item): string
    {
        $rowId = $this->getCart()->add($item);

        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action' => 'add',
            'rowId' => $rowId,
            'item' => $item,
        ]);

        $this->syncCartState();

        return $rowId;
    }

    /** Update cart item */
    public function updateCartItem(string $rowId, array $data): void
    {
        $this->getCart()->update($rowId, $data);

        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action' => 'update',
            'rowId' => $rowId,
            'data' => $data,
        ]);

        $this->syncCartState();
    }

    /** Remove item from cart */
    public function removeFromCart(string $rowId): void
    {
        $this->getCart()->remove($rowId);

        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action' => 'remove',
            'rowId' => $rowId,
        ]);

        $this->syncCartState();
    }

    /** Clear entire cart */
    public function clearCart(): void
    {
        $this->getCart()->destroy();

        $this->dispatch('cart-updated', [
            'instance' => $this->cartInstance,
            'action' => 'clear',
        ]);

        $this->syncCartState();
    }

    /** Listen for cart updates from other components */
    #[On('cart-updated')]
    public function onCartUpdated(array $data): void
    {
        if (isset($data['instance']) && $data['instance'] === $this->cartInstance) {
            $this->syncCartState();
        }
    }
}
