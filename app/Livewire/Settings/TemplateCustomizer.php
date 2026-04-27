<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class TemplateCustomizer extends Component
{
    public $primary_color;

    public $secondary_color;

    public $font_family;

    public $pattern_style;

    public function mount()
    {
        $styles = settings('template_styles') ?? [];

        $this->primary_color = $styles['primary_color'] ?? '#4f46e5';
        $this->secondary_color = $styles['secondary_color'] ?? '#f3f4f6';
        $this->font_family = $styles['font_family'] ?? 'Inter, sans-serif';
        $this->pattern_style = $styles['pattern_style'] ?? 'none';
    }

    public function updatedPrimaryColor(): void
    {
        $this->persistTemplateStyles();
    }

    public function updatedSecondaryColor(): void
    {
        $this->persistTemplateStyles();
    }

    public function updatedFontFamily(): void
    {
        $this->persistTemplateStyles();
    }

    public function updatedPatternStyle(): void
    {
        $this->persistTemplateStyles();
    }

    private function persistTemplateStyles(): void
    {
        Setting::set('template_styles', [
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'font_family' => $this->font_family,
            'pattern_style' => $this->pattern_style,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.template-customizer');
    }
}
