<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use Livewire\Component;
use App\Models\Language;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class EditTranslation extends Component
{
    use LivewireAlert;

    public $language;

    public $translations;

    public $rules = [
        'translations.*.value' => 'required',
    ];

    public function mount($code)
    {
        $this->language = Language::where('code', $code)->firstOrFail();
        $this->translations = $this->getTranslations();
        $this->translations = collect($this->translations)->map(function ($item, $key) {
            return [
                'key'   => $key,
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

        if ( ! file_exists($path)) {
            $this->alert('error', __('File does not exist!'));

            return;
        }

        try {
            $json = file_get_contents($path);
            $translations = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->alert('error', __('Error decoding JSON data!'));

            return;
        }

        foreach ($this->translations as $key => $translation) {
            $translations[$translation['key']] = $translation['value'];
        }

        try {
            file_put_contents($path, json_encode($translations, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (JsonException $e) {
            $this->alert('error', __('Error encoding JSON data!'));

            return;
        }

        $this->alert('success', __('Translated  successfully!'));
    }

    public function render()
    {
        return view('livewire.language.edit-translation');
    }
}
