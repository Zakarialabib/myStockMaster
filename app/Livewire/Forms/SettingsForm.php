<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingsForm extends Form
{
    public ?Setting $setting = null;

    #[Validate('required|string|min:1|max:255')]
    public string $company_name = '';

    #[Validate('required|string|email|min:1|max:255')]
    public string $company_email = '';

    #[Validate('required|string|min:1|max:255')]
    public string $company_phone = '';

    #[Validate('required|string|min:1|max:255')]
    public string $company_address = '';

    #[Validate('required|integer')]
    public int $default_currency_id = 1;

    #[Validate('required|string')]
    public string $default_currency_position = 'prefix';

    #[Validate('nullable|string|email|max:255')]
    public ?string $notification_email = null;

    #[Validate('nullable|string')]
    public ?string $footer_text = null;

    #[Validate('nullable|string|max:255')]
    public ?string $company_tax = null;

    #[Validate('boolean')]
    public bool $is_ecommerce_active = false;

    public function setSetting(Setting $setting): void
    {
        $this->setting = $setting;

        $this->company_name = $setting->company_name ?? '';
        $this->company_email = $setting->company_email ?? '';
        $this->company_phone = $setting->company_phone ?? '';
        $this->company_address = $setting->company_address ?? '';
        $this->default_currency_id = $setting->default_currency_id ?? 1;
        $this->default_currency_position = $setting->default_currency_position ?? 'prefix';
        $this->notification_email = $setting->notification_email;
        $this->footer_text = $setting->footer_text;
        $this->company_tax = $setting->company_tax;
        $this->is_ecommerce_active = (bool) $setting->is_ecommerce_active;
    }

    public function update(): void
    {
        $this->validate();

        $this->setting->update([
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => $this->company_phone,
            'company_address' => $this->company_address,
            'default_currency_id' => $this->default_currency_id,
            'default_currency_position' => $this->default_currency_position,
            'notification_email' => $this->notification_email,
            'footer_text' => $this->footer_text,
            'company_tax' => $this->company_tax,
            'is_ecommerce_active' => $this->is_ecommerce_active,
        ]);
    }
}
