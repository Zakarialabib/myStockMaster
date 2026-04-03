<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Livewire\Utils\Datatable;
use App\Livewire\Utils\HasDelete;
use App\Livewire\Utils\WithModels;
use App\Models\Quotation;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use Datatable;
    use HasDelete;
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public $quotation;

    public $model = Quotation::class;

    public function render()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $query = Quotation::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
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
