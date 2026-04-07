<?php

declare(strict_types=1);

namespace App\Livewire\Currency;

use App\Livewire\Utils\Datatable;
use App\Models\Currency;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Currency')]
class Index extends Component
{
    use Datatable;
    use WithAlert;

    public mixed $currency;

    public string $model = Currency::class;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('currency_access'), 403);

        $query = Currency::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.currency.index', ['currencies' => $lengthAwarePaginator]);
    }

    public function delete(Currency $currency): void
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency->delete();

        $this->alert('success', __('Currency deleted successfully!'));
    }
}
