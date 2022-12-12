<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ToggleButton extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $status;

    public $uniqueId;

    protected $listeners = ['updating'];

    public function mount()
    {
        $this->status = (bool) $this->model->getAttribute($this->field);
        $this->uniqueId = uniqid();
    }

    public function updating($field, $value)
    {
        $this->model->setAttribute($this->field, $value)->save();

        $this->alert('success', __('Status Changed successfully!'));
    }

    public function render()
    {
        return view('livewire.toggle-button');
    }
}
