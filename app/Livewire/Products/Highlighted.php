<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Product;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Highlighted extends Component
{
    use WithAlert;

    public mixed $product;

    public mixed $hot;

    public mixed $featured;

    public mixed $best;

    public mixed $top;

    public mixed $latest;

    public mixed $big;

    public mixed $trending;

    public string $sale = '';

    public mixed $is_discount;

    public mixed $discount_date;

    public bool $highlightModal = false;

    #[On('highlightModal')]
    public function highlightModal(mixed $id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = Product::query()->findOrFail($id);

        $this->highlightModal = true;
    }

    public function saveHighlight(): void
    {
        try {
            if ($this->hot) {
                $this->product->hot = $this->hot;
            }

            if ($this->featured) {
                $this->product->featured = $this->featured;
            }

            if ($this->best) {
                $this->product->best = $this->best;
            }

            if ($this->top) {
                $this->product->top = $this->top;
            }

            if ($this->latest) {
                $this->product->latest = $this->latest;
            }

            if ($this->big) {
                $this->product->big = $this->big;
            }

            if ($this->trending) {
                $this->product->trending = $this->trending;
            }

            if ($this->sale) {
                $this->product->sale = $this->sale;
            }

            if ($this->is_discount) {
                $this->product->is_discount = $this->is_discount;
            }

            if ($this->discount_date) {
                $this->product->discount_date = $this->discount_date;
            }

            $this->product->save();

            $this->alert('success', 'Product highlighted successfully.');

            $this->highlightModal = false;
        } catch (Throwable) {
            $this->alert('success', 'Unable to updated Product highlighted.');
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.products.highlighted');
    }
}
