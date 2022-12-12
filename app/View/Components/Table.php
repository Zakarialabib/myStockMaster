<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Closure;

class Table extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|Closure|string
     */
    public function render()
    {
        return view('components.table');
    }
}
