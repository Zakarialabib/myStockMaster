<?php

declare(strict_types=1);

namespace App\Livewire\Shipping;

use App\Livewire\Utils\Datatable;
use App\Models\Shipping;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;

    public mixed $shipping;

    public string $model = Shipping::class;

    public function confirmed(): void
    {
        $this->dispatch('delete');
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('shipping_access'), 403);

        $query = Shipping::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.shipping.index', ['shippings' => $lengthAwarePaginator]);
    }

    public function deleteModal(int|string $shipping): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);
        $this->shipping = $shipping;
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies('shipping_delete'), 403);

        Shipping::query()->findOrFail($this->shipping)->delete();

        $this->alert('success', __('Shipping deleted successfully.'));
    }
}
