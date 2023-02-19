<?php

declare(strict_types=1);

namespace App\Http\Livewire\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createBrand = false;

    /** @var mixed */
    public $brand;

    public $image;

    public $name;

    public $description;

    /** @var string[] */
    public $listeners = ['createBrand'];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /** @var array */
    protected $rules = [
        'name'        => 'required|min:3|max:255',
        'description' => 'nullable',
        'image'       => 'nullable|image|max:1024',
    ];

    public function render()
    {
        abort_if(Gate::denies('brand_create'), 403);

        return view('livewire.brands.create');
    }

    public function hydrate()
    {
        // $this->image = $imageName;
    }

    public function createBrand(): void
    {
        $this->reset();

        $this->createBrand = true;
    }

    public function create(): void
    {
        $validatedData = $this->validate();

        // image not working with realtime validation
        if ($this->image) {
            $imageName = Str::slug($this->name).'-'.Str::random(5).'.'.$this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $this->image = $imageName;
        }

        Brand::create($validatedData);

        $this->emit('refreshIndex');

        $this->alert('success', __('Brand created successfully.'));

        $this->createBrand = false;
    }
}
