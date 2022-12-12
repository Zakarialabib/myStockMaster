<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class ToggleButton extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.toggle-button');
    }
}
