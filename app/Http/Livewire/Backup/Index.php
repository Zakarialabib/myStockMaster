<?php

declare(strict_types=1);

namespace App\Http\Livewire\Backup;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    use LivewireAlert;

    public $data = [];

    public $backup_status;
    public $backup_schedule;
    public $backup_include;

    protected array $rules = [
        'backup_status' => 'required',
        'backup_schedule' => 'nullable',
    ];
    public $settingsModal = false;

    protected $listeners = [
        'deleteModel', 'generate',
        'refreshTable' => '$refresh',
        'delete',
    ];
    
    public function cleanBackups()
    {
        Artisan::call('backup:clean');

        $this->alert('success', __('Old backup cleaned.'));
    }

    public function settingsModal()
    {
        $this->backup_status = settings()->backup_status;
        $this->backup_schedule = settings()->backup_schedule;
        $this->settingsModal = true;
    }
    
    public function updateSettigns()
    {
        try {
            $this->validate();

            settings()->update([
                'backup_status' => $this->backup_status,
                'backup_schedule' => $this->backup_schedule,
            ]);

            $this->alert('success', __('Settings backuped saved.'));

            $this->settingsModal = false;

        } catch (\Throwable $th) {
            $this->alert('success', __('Failed.'.$th->getMessage()));
        }
    }

    public function render()
    {
        $files = Storage::allFiles(env('APP_NAME'));

        return view('livewire.backup.index', [
            'backups' => $files,
        ]);
    }

    public function generate()
    {
        try {
            Artisan::call('backup:run --only-db');
            $this->alert('success', __('Backup Generated with success.'));
        } catch (Throwable $th) {
            $this->alert('success', __('Database backup failed.'));
        }
    }

    public function downloadBackup($file)
    {
        return Storage::download($file);
    }

    public function delete($name)
    {
        foreach (glob(storage_path().'/app/public/backup/*') as $filename) {
            $path = storage_path().'/app/public/backup/'.basename($name);

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }
}
