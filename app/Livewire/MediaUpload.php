<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Modelable;

class MediaUpload extends Component
{
    use WithFileUploads;

    #[Modelable]
    public $file = null;

    public $name = '';
    public $title = 'Upload File';
    public $single = true;
    public $types = '';
    public $fileTypes = '*';
    public $maxSize = 0;
    public $image = null;
    public $preview = true;
    public $maxFiles = 0;

    public function render()
    {
        return view('livewire.media-upload');
    }

    public function removeSingle()
    {
        $this->file = null;
    }

    public function removeMultiple($index)
    {
        if (is_array($this->file) && isset($this->file[$index])) {
            unset($this->file[$index]);
            $this->file = array_values($this->file);
        }
    }
}
