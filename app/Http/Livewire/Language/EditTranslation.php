<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EditTranslation extends Component
{
    use LivewireAlert;
    public $language;
    public $translations;

    public $rules = [
        'translations.*.value' => 'required',
    ];

    public function mount($language)
    {
        $this->language = Language::where('id', $language)->firstOrFail();
        // dd($this->all());
        $this->translations = $this->getTranslations();
        $this->translations = collect($this->translations)->map(function ($item, $key) {
            return [
                'key' => $key,
                'value' => $item,
            ];
        })->toArray();
    }

    private function getTranslations()
    {
        $path = base_path("lang/{$this->language->code}.json");
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    public function updateTranslation()
    {
        $this->validate();

        $path = base_path("lang/{$this->language->code}.json");

        $data = file_get_contents($path);
        $translations = json_decode($data, true);
    
        foreach ($this->translations as $key => $translation) {
            $translations[$translation['key']] = $translation['value'];
        }
    
        file_put_contents($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->alert('success', __('Data created successfully!'));

    }

    public function render()
    {
        return view('livewire.language.edit-translation');
    }
}
