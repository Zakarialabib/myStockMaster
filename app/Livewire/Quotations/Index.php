<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\WithModels;
use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Models\Quotation;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Traits\WithAlert;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithAlert;
    use Datatable;
    use WithFileUploads;
    use WithModels;
    use HasDelete;

    public $quotation;

    public $model = Quotation::class;

    public function render()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $query = Quotation::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $quotations = $query->paginate($this->perPage);

        return view('livewire.quotations.index', ['quotations' => $quotations]);
    }

    protected function getGateDelete(): string
    {
        return 'quotation_delete';
    }
}
