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
    
        if ($updated) {
            return __('Project fetched');
        } elseif ($updatedPush){
            return __('updating project');
        } 
        
        return __('Error updating project');
    }

    public function mount()
    {
        $this->message = __('Upgrade to latest version');
    }


    public function render()
    {
        return view('livewire.settings.update');
    }
}
