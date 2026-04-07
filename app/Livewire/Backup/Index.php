<?php

declare(strict_types=1);

namespace App\Livewire\Backup;

use App\Traits\WithAlert;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

#[Layout('layouts.app')]

class Index extends Component
{
    use WithAlert;

    #[Validate('required')]
    public $backup_status;

    #[Validate('nullable')]
    public $backup_schedule;

    public $backup_include;

    public $settingsModal = false;

    public function settingsModal(): void
    {
        $this->backup_status = settings()->backup_status;
        $this->backup_schedule = settings()->backup_schedule;
        $this->settingsModal = true;
    }

    public function syncToLocal(): void
    {
        Artisan::call('db:production-sync');

        $this->alert('success', __('Database synced.'));
    }

    public function cleanBackups(): void
    {
        Artisan::call('backup:clean');

        $this->alert('success', __('Old backup cleaned.'));
    }

    public function updateSettings(): void
    {
        try {
            $this->validate();

            settings()->update([
                'backup_status' => $this->backup_status,
                'backup_schedule' => $this->backup_schedule,
            ]);

            $this->alert('success', __('Settings backuped saved.'));

            $this->settingsModal = false;
        } catch (Throwable $throwable) {
            $this->alert('error', __('Failed.') . ' ' . $throwable->getMessage());
        }
    }

    public function generate(): void
    {
        try {
            Artisan::call('backup:run --only-db');
            $this->alert('success', __('Backup Generated with success.'));
        } catch (Throwable) {
            $this->alert('error', __('Database backup failed.'));
        }
    }

    public function downloadBackup(string $file): StreamedResponse
    {
        return Storage::download($file);
    }

    public function delete(string $name): void
    {
        Storage::delete($name);

        $this->alert('success', __('Backup deleted successfully.'));
    }

    public function render()
    {
        $files = Storage::allFiles(env('APP_NAME'));

        return view('livewire.backup.index', [
            'backups' => $files,
        ]);
    }
}
