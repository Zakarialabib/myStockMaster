<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Models\Brand;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Delete extends Component
{
    use WithAlert;

    public bool $showModal = false;

    public Brand $brand;

    #[On('deleteModal')]
    public function openDeleteModal(Brand $brand): void
    {
        abort_if(Gate::denies('brand_delete'), 403);

        $this->brand = $brand;

        $this->resetErrorBag();

        $this->resetValidation();

        $this->showModal = true;
    }

    public function delete(): void
    {
        $this->brand->delete();

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->alert('success', __('Brand deleted successfully.'));

        $this->showModal = false;
    }

    public function render()
    {
        abort_if(Gate::denies('brand_delete'), 403);

        return view('livewire.brands.delete');
    }
}
