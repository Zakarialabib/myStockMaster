<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ToggleButton extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $status;

    public $uniqueId;

    /** @var string[] */
    protected $listeners = ['updating'];

    public function mount(): void
    {
        $this->status = (bool) $this->model->getAttribute($this->field);
        $this->uniqueId = uniqid();
    }

    /**
     * @param mixed $field
     * @param mixed $value
     * @return void
     */
    public function updating($field, $value)
    {
        $this->model->setAttribute($this->field, $value)->save();

        $this->alert('success', __('Status Changed successfully!'));
    }

    public function render(): View|Factory
    {
        return view('livewire.toggle-button');
    }
}
