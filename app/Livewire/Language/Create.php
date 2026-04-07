<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use App\Models\Language;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    use WithAlert;

    public array $languages = [];

    public mixed $language;

    #[Validate('required|max:191')]
    public mixed $name;

    #[Validate('required')]
    public mixed $code;

    public bool $createModal = false;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        Language::query()->create([
            'name' => $this->name,
            'code' => $this->code,
        ]);

        File::copy(lang_path('en.json'), lang_path($this->code . '.json'));

        $this->alert('success', __('Language created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.language.create');
    }
}
