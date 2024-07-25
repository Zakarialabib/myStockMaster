<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ToggleButton extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $status;

    public $uniqueId;

    /** @var array<string> */
    protected $listeners = ['updating'];

    public function mount(): void
    {
        $this->status = (bool) $this->model->getAttribute($this->field);
        $this->uniqueId = uniqid();
    }

    /**
     * @param mixed $field
     * @param mixed $value
     *
     * @return void
     */
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
