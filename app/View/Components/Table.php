<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public function render()
    {
        return view('components.table');
    }
}
