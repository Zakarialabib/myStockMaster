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

    #[On('deleteModal')]
    public function deleteModal($value): void
    {
        $this->confirm(__('Are you sure you want to delete this?'), [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => __('Cancel'),
            'onConfirmed' => 'delete',
        ]);

        $this->value = $value;
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        try {
            $modelClass = $this->model;
            $modelClass::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->alert('success', __('Items deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->alert('error', __('Some items cannot be deleted because they have related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the items.'));
            }
        }
    }

    #[On('delete')]
    public function delete(): void
    {
        abort_if(Gate::denies($this->getGateDelete()), 403);

        try {
            $this->model::findOrFail($this->value)->delete();
            $this->alert('success', __('Item deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->alert('error', __('Cannot delete this item because it has related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the item.'));
            }
        }
    }

    protected function getGateDelete(): string
    {
        $model = strtolower(class_basename($this->model));

        return $model . ' delete';
    }
}
