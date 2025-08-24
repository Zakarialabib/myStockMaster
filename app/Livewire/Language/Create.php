<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use App\Models\Language;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Validate;
use App\Traits\WithAlert;

class Create extends Component
{
    use WithAlert;
    public array $languages = [];

    public $language;

    #[Validate('required|max:191')]
    public $name;

    #[Validate('required')]
    public $code;

    public $createModal = false;

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        Language::create([
            'name' => $this->name,
            'code' => $this->code,
        ]);

        File::copy(App::langPath().('/en.json'), App::langPath().('/'.$this->code.'.json'));

        $this->alert('success', __('Language created successfully!'));

        $this->dispatch('refreshIndex')->to(Index::class);

        $this->createModal = false;
    }

    public function render()
    {
        return view('livewire.language.create');
    }
}
