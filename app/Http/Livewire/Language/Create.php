<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App;
use App\Models\Language;
use File;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = ['createModal'];

    public array $languages = [];

    public $language;

    public $createModal = false;

    protected $rules = [
        'language.name' => 'required|max:255',
        'language.code' => 'required|max:255|unique:languages,code',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function createModal()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->language = new Language();

        $this->createModal = true;
    }

    public function create()
    {
        try {
            $validatedData = $this->validate();

            $this->language->save($validatedData);

            File::copy(App::langPath().'/en.json', App::langPath().('/'.$this->language->code.'.json'));

            $this->alert('success', __('Language created successfully!'));

            $this->emit('refreshIndex');

            $this->createModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Language was not created!').$th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.language.create');
    }
}
