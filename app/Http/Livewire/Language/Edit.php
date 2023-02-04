<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use Livewire\Component;
use File;
use App;
use App\Models\Language;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;

    /** @var string[] */
    public $listeners = ['editLanguage'];

    public array $languages = [];

    public $language;
    public $name;
    public $code;

    public $editLanguage = false;

    protected $rules = [
        'language.name' => 'required|max:191',
        'language.code' => 'required',
    ];

    public function editLanguage($id)
    {
        $this->language = Language::findOrFail($id);

        $this->editLanguage = true;
    }

    public function update()
    {
        $this->validate();

        $this->language->save();

        File::copy(App::langPath().('/en.json'), App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->emit('resetIndex');

        $this->editLanguage = false;
    }

    public function render()
    {
        return view('livewire.language.edit');
    }
}
