<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Models\Brand;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    use WithAlert;

    public mixed $brand;

    public bool $showModal = false;

    #[On('showModal')]
    public function openShowModal(mixed $id): void
    {
        abort_if(Gate::denies('brand_show'), 403);

        $this->brand = Brand::query()->findOrFail($id);

        $this->showModal = true;
    }

    public function render(): View|Factory
    {
        return view('livewire.brands.show');
    }
}
