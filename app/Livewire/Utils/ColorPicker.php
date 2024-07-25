<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class ColorPicker extends Component
{
    #[Modelable]
    public $value = '';

    public $colors;

    public $title = 'Select a color';

    public $color;

    // Property to be updated (e.g., 'bg_color', 'text_color')
    public $colorOptions = [100, 200, 300, 400, 500, 600, 700, 800, 900];

    public $selectedColor;

    public function mount(): void
    {
        $this->colors = ['gray', 'red', 'green', 'blue', 'indigo'];
    }

    public function showColorPalette($color): void
    {
        $this->selectedColor = $color;
    }

    public function selectColor($color): void
    {
        $this->value = $color;
        // $this->dispatch('selectColor');
    }

    public function render()
    {
        return view('livewire.utils.color-picker');
    }
}
