<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use App\Models\Language;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    use WithAlert;

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

        File::copy(lang_path('en.json'), lang_path($this->code . '.json'));

        $this->alert('success', __('Data created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->editModal = false;
    }

    public function render()
    {
        return view('livewire.language.edit');
    }
}
