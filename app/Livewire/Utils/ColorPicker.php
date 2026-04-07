<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Traits\WithAlert;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class ColorPicker extends Component
{
    use WithAlert;

    #[Modelable]
    public $value = '';

    public mixed $colors;

    public $title = 'Select a color';

    public mixed $color;

    // Property to be updated (e.g., 'bg_color', 'text_color')
    public $colorOptions = [100, 200, 300, 400, 500, 600, 700, 800, 900];

    public mixed $selectedColor;

    public function mount(): void
    {
        $this->colors = ['gray', 'red', 'green', 'blue', 'indigo'];
    }

    public function showColorPalette(mixed $color): void
    {
        $this->selectedColor = $color;
    }

    public function selectColor(mixed $color): void
    {
        $this->value = $color;
        // $this->dispatch('selectColor');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.utils.color-picker');
    }
}
