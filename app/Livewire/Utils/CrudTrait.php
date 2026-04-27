<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;

trait CrudTrait
{
    use HasDelete;
    use ModalTrait;
    use WithAlert;

    public function authorizeAction(string $ability): void
    {
        abort_if(Gate::denies($ability), 403);
    }

    public function handleBulkAction(string $action, array $selected): void
    {
        match ($action) {
            'delete' => $this->deleteSelected(),
            'export' => $this->exportSelected(),
            default => $this->alert('error', 'Unknown bulk action.'),
        };
    }

    protected function getModelClass(): string
    {
        return $this->model ?? static::class;
    }
}
