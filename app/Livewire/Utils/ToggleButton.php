<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Database\Eloquent\Model;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;

class ToggleButton extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $status;

    public $uniqueId;

    public function mount(): void
    {
        $this->status = (bool) $this->model->getAttribute($this->field);
        $this->uniqueId = uniqid();
    }

    #[On('updating')]
    public function updating($field, $value): void
    {
        $this->model->setAttribute($this->field, $value)->save();

        $this->alert('success', __('Status Changed successfully!'));
    }

    public function render()
    {
        return view('livewire.utils.toggle-button');
    }
}
