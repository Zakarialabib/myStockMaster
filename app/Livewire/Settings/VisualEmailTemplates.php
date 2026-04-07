<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Traits\WithAlert;
use Livewire\Component;

class VisualEmailTemplates extends Component
{
    use WithAlert;

    public array $mailStyles = [
        'primary_color' => '#4f46e5',
        'text_color' => '#374151',
        'background_color' => '#f3f4f6',
        'button_radius' => 'rounded-md',
    ];

    public function mount(): void
    {
        $settings = Setting::first();
        if ($settings && $settings->mail_styles) {
            $this->mailStyles = array_merge($this->mailStyles, $settings->mail_styles);
        }
    }

    public function updatedMailStyles(): void
    {
        $settings = Setting::first();
        $settings->update(['mail_styles' => $this->mailStyles]);

        $this->alert('success', __('Email template styles updated successfully!'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.visual-email-templates');
    }
}
