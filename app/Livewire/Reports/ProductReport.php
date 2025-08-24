<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Traits\WithAlert;

#[Layout('layouts.app')]
class ProductReport extends Component
{
    use WithAlert;

    public function render()
    {
        return view('livewire.reports.product-report');
    }
}
