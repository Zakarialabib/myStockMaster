<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use File, App;

class Create extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['createLanguage'];

    public array $languages = [];

    public $language;
    public $name;
    public $code;

    public $createLanguage = false;

    protected $rules = [
        'name' => 'required|max:191',
        'code' => 'required',
    ];

    public function createLanguage()
    {
        $this->createLanguage = true;
    }

    public function create()
    {
        $this->validate();

        $this->language->save();

        File::copy(App::langPath().('/en.json'), App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->emit('resetIndex');

        $this->createLanguage = false;
    }

    public function render()
    {
        return view('livewire.language.create');
    }
}
