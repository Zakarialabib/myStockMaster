<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use App;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EditTranslation extends Component
{
    public $key;
    public $value;
    public $lang;
    public $language;
    public $langList;

    public $rules = [
        'translations.*.value' => 'required',
    ];

    protected $rules = [
        'key'   => 'required',
        'value' => 'required',
    ];

    public function mount($id)
    {
        $this->language = Language::find($id);

        $this->langList = Language::all();
        $this->key = $this->la->key;
        $this->value = $this->la->value;

        if (empty($json)) {
            $this->editWord = false;
        }

        $json = json_decode($json);

        $this->editWord = false;

        return compact('json', 'list_lang', 'la', 'json');
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
