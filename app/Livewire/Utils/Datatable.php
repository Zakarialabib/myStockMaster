<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

trait Datatable
{
    use WithPagination;

    public int $perPage = 25;

    /** @var array<int, string> */
    public array $orderable = [];

    /** @var array<int, string> */
    public array $filterable = [];

    #[Url(history: false, keep: true)]
    public string $search = '';

    /** @var array<int, mixed> */
    public array $selected = [];

    /** @var array<int, int> */
    public array $paginationOptions = [];

    public bool $selectPage = false;

    #[Url(history: true)]
    public string $sortBy = '';

    #[Url(history: true)]
    public string $sortDirection = '';

    public function mountDatatable(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->paginationOptions = [25, 50, 100];
        $this->orderable = (new $this->model)->orderable ?? [];
        $this->filterable = (new $this->model)->filterable ?? [];
    }

    public function sortingBy(string $field): void
    {
        if ($field !== $this->sortBy) {
            $this->sortDirection = 'asc';
        } else {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }

        $this->sortBy = $field;
    }

    #[Computed]
    public function selectedCount(): int
    {
        return count($this->selected);
    }

    #[On('refreshIndex')]
    public function refreshIndex(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
        $this->selectPage = false;
    }
}
