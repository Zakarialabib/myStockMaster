<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithAlert;
    use WithFileUploads;

    public Product $product;

    public mixed $productWarehouses;

    public ProductForm $form;

    public function addOption(): void
    {
        $this->form->options[] = [
            'type' => '',
            'value' => '',
        ];
    }

    public function removeOption(mixed $index): void
    {
        unset($this->form->options[$index]);
        $this->form->options = array_values($this->form->options);
    }

    public function mount(int|string $id): void
    {
        $this->product = Product::query()->findOrFail($id);
        $this->productWarehouses = $this->product->warehouses;
        $this->form->setProduct($this->product);
    }

    public function update(ProductService $productService): void
    {
        $this->form->validate();

        $productService->update($this->product, $this->form->all());

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Product updated successfully.'));
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function brands()
    {
        return Brand::query()->pluck('name', 'id')->toArray();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('product update'), 403);

        return view('livewire.products.edit');
    }
}
