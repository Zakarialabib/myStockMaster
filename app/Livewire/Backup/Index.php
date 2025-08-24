<?php

declare(strict_types=1);

namespace App\Livewire\Backup;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Throwable;
use App\Traits\WithAlert;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithAlert;
    public $data = [];

    public $backup_status;

    public $backup_schedule;

    public $backup_include;



    public $settingsModal = false;

    protected array $rules = [
        'backup_status'   => 'required',
        'backup_schedule' => 'nullable',
    ];

    protected $listeners = [
        'deleteModel', 'generate',
        'refreshTable' => '$refresh',
        'delete',
    ];

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



    public function updateSettigns(): void
    {
        try {
            $this->validate();

            settings()->update([
                'backup_status'   => $this->backup_status,
                'backup_schedule' => $this->backup_schedule,
            ]);

            $this->alert('success', __('Settings backuped saved.'));

            $this->settingsModal = false;
        } catch (Throwable $throwable) {
            $this->alert('success', __('Failed.'.$throwable->getMessage()));
        }
    }

    public function generate(): void
    {
        try {
            Artisan::call('backup:run --only-db');
            $this->alert('success', __('Backup Generated with success.'));
        } catch (Throwable) {
            $this->alert('success', __('Database backup failed.'));
        }
    }

    public function downloadBackup($file)
    {
        return response()->streamDownload($file);
    }

    public function delete($name): void
    {
        foreach (glob(storage_path().'/app/*') as $filename) {
            $path = storage_path().'/app/'.basename((string) $name);

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }



    public function render()
    {
        $files = Storage::allFiles(env('APP_NAME'));

        return view('livewire.backup.index', [
            'backups' => $files,
        ]);
    }
}
