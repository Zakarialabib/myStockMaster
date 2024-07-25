<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

trait HasDelete
{
    public $value;

    public function confirmed(): void
    {
        $this->dispatchSelf('delete');
    }

    public function deleteModal($value): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast'             => false,
            'position'          => 'center',
            'showConfirmButton' => true,
            'cancelButtonText'  => __('Cancel'),
            'onConfirmed'       => 'delete',
        ]);
        $this->value = $value;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        $modelClass = $this->model;
        $modelClass::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        $this->model::findOrFail($this->value)->delete();

        $this->alert('success', __('Item deleted successfully.'));
    }

    protected function getGateDelete(): string
    {
        $model = strtolower(class_basename($this->model));

        return $model.' delete';
    }
}
