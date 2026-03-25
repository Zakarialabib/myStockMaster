<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Traits\WithAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductReport extends Component
{
    use WithAlert;

    public function render()
    {
        return view('livewire.reports.product-report');
    }
}
