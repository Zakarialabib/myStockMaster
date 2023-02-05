<?php

namespace App\Http\Livewire\Settings;

use App\Helpers\GitHandler;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class Update extends Component
{
    protected $listeners = [
        'updateProject'
    ];

    public $message = "";

    public function updateProject()
    {
      
        $gitHandler = new GitHandler();
        $updated = $gitHandler->fetchAndPull();
        // $updatedPush = $gitHandler->pushChanges();
    
        if ($updated) {
            return "Project featched";
        } elseif ($updatedPush){
            return "updating project";
    } else {
            return "Error updating project";
        }

    }

    public function mount()
    {
        $this->message = 'Update your  system with the latest version';
    }

    public function optimize()
    {
        //  Artisan::call('optimize:clear');
        // Artisan::call('optimize');
        // exec("composer dump");
    }

    public function render()
    {
        return view('livewire.settings.update');
    }
}
