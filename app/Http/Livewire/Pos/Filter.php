<?php

namespace App\Http\Livewire\Pos;

use App\Models\Warehouse;
use Livewire\Component;

class Filter extends Component
{
    public $categories;
    public $category;
    public $showCount;
    public $warehouses;

    public array $listsForFields = [];

    public function mount($categories) {
        $this->categories = $categories;
        $this->initListsForFields();
    }

    public function render() {
        return view('livewire.pos.filter');
    }

    public function updatedCategory() {
        $this->emitUp('selectedCategory', $this->category);
    }

    public function updatedWarehouse() {
        $this->emitUp('selectedWarehouse', $this->warehouse);
    }

    public function updatedShowCount() {
        $this->emitUp('showCount', $this->category);
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
    }
}
