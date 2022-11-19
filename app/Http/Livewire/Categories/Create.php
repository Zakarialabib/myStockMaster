<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Create extends Component
{
    use LivewireAlert;
    
    public $listeners = ['createCategory'];

    public $createCategory;

    public $category;

    public $name;
    
    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        return view('livewire.categories.create');
    }

    public function createCategory()
    {
        $this->reset();

        $this->createCategory = true;
    }

    public function create()
    {
        $validatedData = $this->validate();

        Category::create($validatedData);

        $this->emit('refreshIndex');
        
        $this->alert('success', __('Category created successfully.'));
        
        $this->createCategory = false;
    }
}
