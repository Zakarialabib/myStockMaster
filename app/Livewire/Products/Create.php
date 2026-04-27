<?php

declare(strict_types=1);

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Livewire\Utils\WithModels;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\Warehouse;
use App\Services\ProductService;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public bool $createModal = false;

    public int $step = 1;

    public ProductForm $form;

    #[Computed]
    public function productAttributes()
    {
        return ProductAttribute::all()->mapWithKeys(fn ($attr) => [$attr->id => ''])->all();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('product_create'), 403);

        return view('livewire.products.create');
    }

    #[On('createModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->step = 1;
        $this->form->unit = 'pcs';
        $this->form->barcode_symbology = 'C128';

        $this->createModal = true;
    }

    public function create(ProductService $productService): void
    {
        $this->form->validate();

        $data = $this->form->all();
        $data['warehouse_id'] = $this->warehouse?->id;

        $productService->create($data);

        $this->alert('success', __('Product created successfully'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }

    #[Computed]
    public function warehouse()
    {
        return Warehouse::query()->select('name', 'id')->first();
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
}
