<?php

declare(strict_types=1);

namespace App\Http\Livewire\Backup;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    use LivewireAlert;

    public $data = [];

    public $backup_status;
    public $backup_schedule;
    public $backup_include;
    public $clientId;
    public $clientSecret;
    public $refreshToken;
    public $folderId;

    public $settingsModal = false;

    protected array $rules = [
        'backup_status'   => 'required',
        'backup_schedule' => 'nullable',
        'clientId'        => 'required',
        'clientSecret'    => 'required',
        'refreshToken'    => 'required',
        'folderId'        => 'required',
    ];

    protected $listeners = [
        'deleteModel', 'generate',
        'refreshTable' => '$refresh',
        'delete',
    ];

    public function settingsModal()
    {
        $this->backup_status = settings()->backup_status;
        $this->backup_schedule = settings()->backup_schedule;
        $this->settingsModal = true;
    }

    public function saveToDriveManually($filename)
    {
        $fileData = Storage::get($filename);
        Storage::cloud()->put(env('APP_NAME').'/'.$filename, $fileData);

        $this->alert('success', __('Backup saved to Google Drive successfully!'));
    }

    public function cleanBackups()
    {
        Artisan::call('backup:clean');

        $this->alert('success', __('Old backup cleaned.'));
    }

    public function backupToDrive()
    {
        try {
            // Generate backup file
            Artisan::call('backup:run --only-db');

            $drive = Storage::disk('google');

            // Get the path to the latest backup
            $backupPath = Storage::allFiles(env('APP_NAME'));
            $latestBackup = end($backupPath);

            // Upload the backup file to Google Drive
            $drive->put($latestBackup, Storage::get($latestBackup));

            $this->alert('success', __('Backup generated and saved to Google Drive.'));
        } catch (Throwable $th) {
            $this->alert('danger', __('Backup failed: '.$th->getMessage()));
        }
    }

    public function updateSettigns()
    {
        try {
            $this->validate();

            settings()->update([
                'backup_status'   => $this->backup_status,
                'backup_schedule' => $this->backup_schedule,
            ]);

            Config::set('filesystems.disks.google.clientId', $this->clientId);
            Config::set('filesystems.disks.google.clientSecret', $this->clientSecret);
            Config::set('filesystems.disks.google.refreshToken', $this->refreshToken);
            Config::set('filesystems.disks.google.folderId', $this->folderId);

            $this->alert('success', __('Settings backuped saved.'));

            $this->settingsModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Failed.'.$th->getMessage()));
        }
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
        foreach (glob(storage_path().'/app/*') as $filename) {
            $path = storage_path().'/app/'.basename($name);

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    public function getContentsProperty()
    {
        $mainDisk = Storage::disk('google');

        return $mainDisk->listContents('', true /* is_recursive */);
    }

    public function render()
    {
        $files = Storage::allFiles(env('APP_NAME'));

        return view('livewire.backup.index', [
            'backups' => $files,
        ]);
    }
}
