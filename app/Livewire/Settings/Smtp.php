<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Smtp extends Component
{
    use LivewireAlert;

    public $mail_mailer;

    public $mail_host;

    public $mail_port;

    public $mail_from_address;

    public $mail_from_name;

    public $mail_username;

    public $mail_password;

    public $mail_encryption;

    public function mount(): void
    {
        $this->mail_mailer = env('MAIL_MAILER');
        $this->mail_host = env('MAIL_HOST');
        $this->mail_port = env('MAIL_PORT');
        $this->mail_from_address = env('MAIL_FROM_ADDRESS');
        $this->mail_from_name = env('MAIL_FROM_ADDRESS');
        $this->mail_username = env('MAIL_USERNAME');
        $this->mail_password = env('MAIL_PASSWORD');
        $this->mail_encryption = env('MAIL_ENCRYPTION');
    }

    public function render()
    {
        return view('livewire.settings.smtp');
    }

    public function update(): void
    {
        $toReplace = [
            'MAIL_MAILER='.env('MAIL_HOST'),
            'MAIL_HOST="'.env('MAIL_HOST').'"',
            'MAIL_PORT='.env('MAIL_PORT'),
            'MAIL_FROM_ADDRESS="'.env('MAIL_FROM_ADDRESS').'"',
            'MAIL_FROM_NAME="'.env('MAIL_FROM_NAME').'"',
            'MAIL_USERNAME="'.env('MAIL_USERNAME').'"',
            'MAIL_PASSWORD="'.env('MAIL_PASSWORD').'"',
            'MAIL_ENCRYPTION="'.env('MAIL_ENCRYPTION').'"',
        ];

        $replaceWith = [
            'MAIL_MAILER='.$this->mail_mailer,
            'MAIL_HOST="'.$this->mail_host.'"',
            'MAIL_PORT='.$this->mail_port,
            'MAIL_FROM_ADDRESS="'.$this->mail_from_address.'"',
            'MAIL_FROM_NAME="'.$this->mail_from_name.'"',
            'MAIL_USERNAME="'.$this->mail_username.'"',
            'MAIL_PASSWORD="'.$this->mail_password.'"',
            'MAIL_ENCRYPTION="'.$this->mail_encryption.'"',
        ];

        try {
            file_put_contents(base_path('.env'), str_replace($toReplace, $replaceWith, file_get_contents(base_path('.env'))));
            Artisan::call('cache:clear');

            $this->alert('success', __('Email configuration updated successfully!'));
        } catch (Exception $exception) {
            $this->alert('error', __($exception->getMessage()));
        }
    }
}
