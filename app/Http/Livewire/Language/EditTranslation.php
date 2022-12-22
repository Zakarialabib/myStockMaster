<?php

declare(strict_types=1);

namespace App\Http\Livewire\Language;

use Livewire\Component;
use App\Models\Language;
use App;

class EditTranslation extends Component
{
    public $key;
    public $value;

    public $editWord = false;

    protected $listeners = [
        'editWord',
    ];

    protected $rules = [
        'key'   => 'required',
        'value' => 'required',
    ];

    public function mount($id)
    {
        $this->la = Language::find($id);

        $this->list_lang = Language::all();
        $this->key = $this->la->key;
        $this->value = $this->la->value;

        if (empty($json)) {
            $this->editWord = false;
        }

        $json = json_decode($json);

        $this->editWord = false;

        return compact('json', 'list_lang', 'la', 'json');
    }

    public function editWord()
    {
        $this->editWord = true;
    }

    public function updateTranslation()
    {
        $this->validate();

        $reqkey = trim($this->key);
        $reqValue = $this->value;
        $lang = Language::find($id);

        $data = file_get_contents(App::langPath().$lang->code.'.json');

        $json_arr = json_decode($data, true);

        $json_arr[$reqkey] = $reqValue;

        file_put_contents(App::langPath().$lang->code.'.json', json_encode($json_arr));
    }

    public function render()
    {
        return view('livewire.language.edit-translation');
    }
}
