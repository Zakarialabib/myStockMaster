<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectList extends Component
{
    public $options;

    /**
     * Create a new component instance.
     *
     * @param mixed $options
     * @param mixed $id
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.select-list');
    }
}