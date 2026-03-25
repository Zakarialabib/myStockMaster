<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Traits\WithAlert;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProductOptions extends Component
{
    use WithAlert;

    public $options;

    public function updatedOptions($options): void
    {
        $options = [];

        foreach ($options as $option) {
            if (! empty($option['type']) && ! empty($option['value'])) {
                $this->options[] = $option;
            }
        }

        $this->dispatch('optionUpdated', $this->options);
    }

    public function addOption(): void
    {
        $this->options[] = [
            'type' => '',
            'value' => '',
        ];
    }

    public function removeOption($index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function mount(): void
    {
        $this->options = [
            [
                'type' => '',
                'value' => '',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.products.product-options');
    }
}
