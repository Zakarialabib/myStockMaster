<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class AppCustomizer extends Component
{
    public $primary_color;
    public $font_family;

    public function mount()
    {
        $style = settings('app_style') ?? [];
        
        $this->primary_color = $style['primary_color'] ?? '#0061ff';
        $this->font_family = $style['font_family'] ?? "'Inter', sans-serif";
    }

    public function updated()
    {
        Setting::set('app_style', [
            'primary_color' => $this->primary_color,
            'font_family' => $this->font_family,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.app-customizer');
    }
}
