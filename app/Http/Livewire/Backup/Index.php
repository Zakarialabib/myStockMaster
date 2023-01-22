<?php

declare(strict_types=1);

namespace App\Http\Livewire\Backup;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    use LivewireAlert;

    public $data = [];

    protected $listeners = [
        'deleteModel', 'generate',
        'refreshTable' => '$refresh',
    ];


    public function render()
    {

        $files = Storage::allFiles( env("APP_NAME") );
        return view('livewire.backup.index',[
            "backups" => $files,
        ]);
    }

    public function generate()
    {
        try{

            Artisan::call("backup:run --only-db");
            $this->alert('success', __('Backup Generated with success.'));

        }catch(Exception $error){
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
