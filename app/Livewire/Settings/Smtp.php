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
        $this->mail_mailer = config('mail.mailer');
        $this->mail_host = config('mail.host');
        $this->mail_port = (string) config('mail.port');
        $this->mail_from_address = config('mail.from.address');
        $this->mail_from_name = config('mail.from.name');
        $this->mail_username = config('mail.username');
        $this->mail_password = config('mail.password');
        $this->mail_encryption = config('mail.encryption');
    }

    public function render()
    {
        return view('livewire.settings.smtp');
    }

    public function update(): void
    {
        $toReplace = [
            'MAIL_MAILER=' . config('mail.mailer'),
            'MAIL_HOST="' . config('mail.host') . '"',
            'MAIL_PORT=' . config('mail.port'),
            'MAIL_FROM_ADDRESS="' . config('mail.from.address') . '"',
            'MAIL_FROM_NAME="' . config('mail.from.name') . '"',
            'MAIL_USERNAME="' . config('mail.username') . '"',
            'MAIL_PASSWORD="' . config('mail.password') . '"',
            'MAIL_ENCRYPTION="' . config('mail.encryption') . '"',
        ];

        $replaceWith = [
            'MAIL_MAILER=' . $this->mail_mailer,
            'MAIL_HOST="' . $this->mail_host . '"',
            'MAIL_PORT=' . $this->mail_port,
            'MAIL_FROM_ADDRESS="' . $this->mail_from_address . '"',
            'MAIL_FROM_NAME="' . $this->mail_from_name . '"',
            'MAIL_USERNAME="' . $this->mail_username . '"',
            'MAIL_PASSWORD="' . $this->mail_password . '"',
            'MAIL_ENCRYPTION="' . $this->mail_encryption . '"',
        ];

        try {
            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);

            if ($envContent === false) {
                throw new Exception('Unable to read .env file');
            }

            $newContent = str_replace($toReplace, $replaceWith, $envContent);

            if (file_put_contents($envPath, $newContent) === false) {
                throw new Exception('Unable to write .env file');
            }

            Artisan::call('cache:clear');

            $this->alert('success', __('Email configuration updated successfully!'));
        } catch (Exception $exception) {
            $this->alert('error', __($exception->getMessage()));
        }
    }
}
