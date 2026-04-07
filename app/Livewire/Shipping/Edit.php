<?php

declare(strict_types=1);

namespace App\Livewire\Shipping;

use App\Models\Shipping;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

    public mixed $shipping;

    public bool $editModal = false;

    public mixed $langauges;

    public bool $is_pickup = false;

    #[Validate('required|max:255')]
    public string $title;

    #[Validate('nullable|max:255')]
    public string $subtitle;

    #[Validate('required|numeric')]
    public mixed $cost;

    public function render(): View|Factory
    {
        abort_if(Gate::denies('shipping update'), 403);

        return view('livewire.shipping.edit');
    }

    #[On('editModal')]
    public function openModal(int|string $id): void
    {
        // abort_if(Gate::denies('shipping_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->shipping = Shipping::query()->findOrFail($id);

        $this->title = $this->shipping->title;

        $this->subtitle = $this->shipping->subtitle;

        $this->cost = $this->shipping->cost;

        $this->is_pickup = $this->shipping->is_pickup;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->shipping->update($this->all());

        $this->alert('success', __('shipping updated successfully'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }
}
