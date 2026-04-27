<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Traits\WithAlert;
use Exception;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

trait HasDelete
{
    use WithAlert;

    public mixed $value;

    /**
     * Components using this trait should define this property or override the method.
     * e.g., protected string $deleteAbility = 'product_delete';
     */
    protected function getDeleteAbility(): string
    {
        if (property_exists($this, 'deleteAbility')) {
            return $this->deleteAbility;
        }

        // Fallback or throw exception to enforce strictness
        throw new Exception('Component using HasDelete must define $deleteAbility property or override getDeleteAbility().');
    }

    public function confirmed(): void
    {
        $this->dispatchSelf('delete');
    }

    #[On('deleteModal')]
    public function deleteModal(int|string $value): void
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
        abort_if(Gate::denies($this->getDeleteAbility()), 403);

        try {
            $modelClass = property_exists($this, 'model') ? $this->model : $this->getModel();
            $modelClass::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->alert('success', __('Items deleted successfully.'));
        } catch (\Illuminate\Database\QueryException $queryException) {
            if ($queryException->getCode() === '23000') {
                $this->alert('error', __('Some items cannot be deleted because they have related records.'));
            } else {
                $this->alert('error', __('An error occurred while deleting the items.'));
            }
        }
    }

    #[On('delete')]
    public function delete(?int $id = null)
    {
        Gate::authorize($this->getDeleteAbility());

        $idToDelete = $id ?? $this->value;
        $modelClass = property_exists($this, 'model') ? $this->model : $this->getModel();

        $model = $modelClass::findOrFail($idToDelete);
        $model->delete();

        return $model . '_delete';
    }
}
