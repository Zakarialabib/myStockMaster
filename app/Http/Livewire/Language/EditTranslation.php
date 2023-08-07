<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App\Models\Language;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\File;

class EditTranslation extends Component
{
    use LivewireAlert;

    public $selectedLanguage;
    public $allLanguages;
    public $translations = [];
    public $newKey = '';
    public $newValue = '';

    protected $rules = [
        'translations.*.value' => 'required',
    ];

    public function mount($id)
    {
        $this->selectedLanguage = Language::find($id);
        $this->allLanguages = Language::all();
        $this->translations = $this->getTranslations();
    }

    private function getTranslations(): array
    {
        $content = File::get($this->languageFilePath());
        return json_decode($content, true);
    }

    private function languageFilePath(): string
    {
        return base_path("lang/{$this->selectedLanguage->code}.json");
    }

    public function updateTranslation()
    {
        $this->validate();

        $translations = $this->getTranslations();
        foreach ($this->translations as $key => $translation) {
            $translations[$translation['key']] = $translation['value'];
        }
        File::put($this->languageFilePath(), json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->alert('success', __('Data updated successfully!'));
    }

    public function deleteTranslation($key)
    {
        unset($this->translations[$key]);
        $this->updateTranslation();
    }

    public function addTranslation()
    {
        $this->translations[$this->newKey] = ['key' => $this->newKey, 'value' => $this->newValue];
        $this->newKey = '';
        $this->newValue = '';
        $this->updateTranslation();
    }

    public function render()
    {
        return view('livewire.language.edit-translation');
    }
}
