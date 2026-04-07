<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Traits\WithAlert;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Smtp extends Component
{
    use WithAlert;

    public ?string $mail_mailer = null;

    public ?string $mail_host = null;

    public ?string $mail_port = null;

    public ?string $mail_from_address = null;

    public ?string $mail_from_name = null;

    public ?string $mail_username = null;

    public ?string $mail_password = null;

    public ?string $mail_encryption = null;

    public function mount(): void
    {
        $settings = \App\Models\Setting::query()->first();
        
        $this->mail_mailer = $settings->mail_mailer ?? config('mail.mailer');
        $this->mail_host = $settings->smtp_host ?? config('mail.host');
        $this->mail_port = (string) ($settings->smtp_port ?? config('mail.port'));
        $this->mail_from_address = $settings->mail_from_address ?? config('mail.from.address');
        $this->mail_from_name = $settings->mail_from_name ?? config('mail.from.name');
        $this->mail_username = $settings->smtp_username ?? config('mail.username');
        $this->mail_password = $settings->smtp_password ?? config('mail.password');
        $this->mail_encryption = $settings->smtp_encryption ?? config('mail.encryption');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.settings.smtp');
    }

    public function update(): void
    {
        try {
            $settings = \App\Models\Setting::query()->first();
            
            if ($settings) {
                $settings->update([
                    'mail_mailer' => $this->mail_mailer,
                    'smtp_host' => $this->mail_host,
                    'smtp_port' => $this->mail_port,
                    'mail_from_address' => $this->mail_from_address,
                    'mail_from_name' => $this->mail_from_name,
                    'smtp_username' => $this->mail_username,
                    'smtp_password' => $this->mail_password,
                    'smtp_encryption' => $this->mail_encryption,
                ]);
            }

            Artisan::call('cache:clear');

            $this->alert('success', __('Email configuration updated successfully!'));
        } catch (Exception $exception) {
            $this->alert('error', __($exception->getMessage()));
        }
    }
}
