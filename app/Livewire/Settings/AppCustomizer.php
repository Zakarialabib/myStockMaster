<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

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

    public function updatedPrimaryColor(): void
    {
        $this->persistAppStyle();
    }

    public function updatedFontFamily(): void
    {
        $this->persistAppStyle();
    }

    private function persistAppStyle(): void
    {
        $settings = [
            'primary_color' => $this->primary_color,
            'font_family' => $this->font_family,
        ];

        Setting::set('app_style', $settings);

        $this->dispatch('theme-updated', settings: $settings);
    }

    public function render()
    {
        return view('livewire.settings.app-customizer');
    }
}
