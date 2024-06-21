<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use App\Models\Language;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use LivewireAlert;

    public array $languages = [];

    public $language;

    #[Validate('required', message: 'The code field is required')]
    public $name;

    #[Validate('required', message: 'The code field is required')]
    public $code;

    public $editModal = false;

    #[On('editModal')]
    public function openEditModal($id): void
    {
        $this->language = Language::findOrFail($id);

        $this->name = $this->language->name;

        $this->code = $this->language->code;

        $this->editModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->language->update([
            'name' => $this->name,
            'code' => $this->code,
        ]);

        File::copy(App::langPath().('/en.json'), App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }

    public function render()
    {
        return view('livewire.language.edit');
    }
}
