<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Livewire\Component;

/**
 * How to open a modal:
 * <button wire:click="$emit('openModal', 'create-product-form')">Create</button>
 * <button wire:click="$emit('openModal', 'edit-product-form', { product: {{ $product->id }} })">Edit</button>
 */
abstract class ModalComponent extends Component
{
    public const modalMaxWidth = '600px';

    public function openModal(string $name)
    {
        $this->emit('openModal', $name);

        return $this;
    }

    public function closeModal(?string $name = null)
    {
        $this->emit('closeModal', $name ?? $this->getName());

        return $this;
    }

    public function closePreviousModal()
    {
        $this->emit('closePreviousModal');

        return $this;
    }
}
