<?php

namespace App\View\Components\Input;

use Illuminate\View\Component;

class DatePicker extends Component
{
    public $name;
    public $value;
    public $label;
    public $placeholder;

    public function __construct($name = 'date', $value = null, $label = 'Select Date', $placeholder = 'Select date')
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input.datepicker');
    }
}
