<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithAlert;

new class extends Component
{
    use WithAlert;

    public Model $model;

    public $field;

    public $status;

    public $uniqueId;

    public function mount(): void
    {
        $this->status = (bool) $this->model->getAttribute($this->field);
        $this->uniqueId = uniqid();
    }

    #[On('toggleStatus')]
    public function toggleStatus($field, $value): void
    {
        $this->model->setAttribute($this->field, $value)->save();

        $this->alert('success', __('Status Changed successfully!'));
    }
};
?>

<div>
    <x-toggle-switch name="status" wire:model="status" 
                    wire:key="status-{{ $model->id }}" 
                    class="text-white" id="{{$uniqueId}}" 
                    checked="{{$status}}"/>
</div>
