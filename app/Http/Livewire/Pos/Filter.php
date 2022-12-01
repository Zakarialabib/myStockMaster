<?php

namespace App\Http\Livewire\Pos;

use App\Models\Warehouse;
use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Filter extends Component
{
    public $categories;

    public $category;

    public $showCount;

    public $warehouse_id;

    public $warehouses;

    public array $listsForFields = [];

    public function mount($categories): void
    {
        $this->categories = $categories;
        $this->initListsForFields();
    }

    public function render(): View|Factory
    {
        return view('livewire.pos.filter');
    }

    public function updatedCategory(): void
    {
        $this->emitUp('selectedCategory', $this->category);
    }

    public function updatedWarehouse(): void
    {
        $this->emitUp('selectedWarehouse', $this->warehouse);
    }

    public function updatedShowCount(): void
    {
        $this->emitUp('showCount', $this->category);
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
