<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App;
use File;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createLanguage'];

    public array $languages = [];

    public $language;
    public $name;
    public $code;

    public $createLanguage = false;

    protected $rules = [
        'name' => 'required|max:255',
        'code' => 'required|max:255',
    ];

    public function createLanguage()
    {
        $this->createLanguage = true;
    }

    public function create()
    {
        $this->validate();

        $this->language->save();

        File::copy(App::langPath().'/en.json', App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->emit('resetIndex');

        $this->createLanguage = false;
    }

    public function render()
    {
        return view('livewire.language.create');
    }
}
