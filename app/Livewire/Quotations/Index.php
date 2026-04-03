<?php

declare(strict_types=1);

namespace App\Livewire\Quotations;

use App\Exports\QuotationExport;
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

    public function downloadSelected()
    {
        abort_if(Gate::denies('quotation_access'), 403);

        $quotations = Quotation::whereIn('id', $this->selected)->get();

        return (new QuotationExport($quotations))->download('quotations.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return $this->callExport()->download('quotations.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return $this->callExport()->forModels($this->selected)->download('quotations.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        abort_if(Gate::denies('quotation_access'), 403);

        return $this->callExport()->download('quotations.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    private function callExport(): QuotationExport
    {
        return new QuotationExport;
    }
}
