<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App;
use App\Models\Language;
use File;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Edit extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['editLanguage'];

    public array $languages = [];

    public $language;
    public $name;
    public $code;

    public $editLanguage = false;

    protected $rules = [
        'language.name' => 'required|max:191',
        'language.code' => 'required|max:255',
    ];

    public function editLanguage($id)
    {
        $this->language = Language::findOrFail($id);

        $this->editLanguage = true;
    }

    public function update()
    {
        $validatedData = $this->validate();

        $this->language->save($validatedData);

        File::copy(App::langPath().'/en.json', App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->emit('refreshIndex');

        $this->editLanguage = false;
    }

    public function render()
    {
        return view('livewire.language.edit');
    }
}
