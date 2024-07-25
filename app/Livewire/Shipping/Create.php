<?php

declare(strict_types=1);

namespace App\Livewire\Shipping;

use App\Models\Shipping;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use LivewireAlert;

    public $createModal = false;

    public Shipping $shipping;

    public bool $is_pickup = false;

    #[Validate('required|max:255')]
    public string $title;

    #[Validate('nullable|max:255')]
    public string $subtitle;

    #[Validate('required|numeric')]
    public $cost;

    public function render()
    {
        abort_if(Gate::denies('shipping_create'), 403);

        return view('livewire.shipping.create');
    }

    #[On('createModal')]
    public function openModal(): void
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        Shipping::create($this->all());

        $this->alert('success', __('Shipping created successfully.'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;

        $this->reset(['title', 'subtitle', 'cost', 'is_pickup']);
    }
}
