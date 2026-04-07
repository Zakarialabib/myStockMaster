<?php

declare(strict_types=1);

namespace App\View\Components\Input;

use Illuminate\View\Component;

class DatePicker extends Component
{
    public function __construct(public $name = 'date', public $value = null, public $label = 'Select Date', public $placeholder = 'Select date')
    {
    }

    public function render()
    {
        return view('components.input.datepicker');
    }
}
