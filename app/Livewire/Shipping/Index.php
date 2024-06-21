<?php

declare(strict_types=1);

namespace App\Livewire\Shipping;

use App\Models\Shipping;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Utils\Datatable;
use Illuminate\Support\Facades\Gate;

#[Layout('layouts.app')]
class Index extends Component
{
    use Datatable;
    use LivewireAlert;

    public $listeners = [
        'delete',
    ];

    public $shipping;

    public $model = Shipping::class;

    public function confirmed(): void
    {
        $this->dispatch('delete');
    }

    public function render(): View|Factory
    {
        abort_if(Gate::denies('shipping_access'), 403);

        $query = Shipping::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $shippings = $query->paginate($this->perPage);

        return view('livewire.shipping.index', ['shippings' => $shippings]);
    }

    public function deleteModal($shipping): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);
        $this->shipping = $shipping;
    }

    public function delete(): void
    {
        abort_if(Gate::denies('shipping_delete'), 403);

        Shipping::findOrFail($this->shipping)->delete();

        $this->alert('success', __('Shipping deleted successfully.'));
    }
}
