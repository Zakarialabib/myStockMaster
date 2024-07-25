<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Highlighted extends Component
{
    use LivewireAlert;

    public $product;

    public $hot;

    public $featured;

    public $best;

    public $top;

    public $latest;

    public $big;

    public $trending;

    public $sale;

    public $is_discount;

    public $discount_date;

    public $listeners = [
        'highlightModal',
    ];

    public $highlightModal = false;

    public function highlightModal($id): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->product = Product::findOrFail($id);

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
