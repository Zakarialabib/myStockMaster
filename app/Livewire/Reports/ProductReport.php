<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProductReport extends Component
{
    public function render()
    {
        return view('livewire.reports.product-report');
    }
}
