<?php

declare(strict_types=1);

namespace App\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public $brand;

    public $showModal = false;

    #[On('showModal')]
    public function openShowModal($id): void
    {
        abort_if(Gate::denies('brand_show'), 403);

        $this->brand = Brand::findOrFail($id);

        $this->showModal = true;
    }

    public function render(): View|Factory
    {
        return view('livewire.brands.show');
    }
}
