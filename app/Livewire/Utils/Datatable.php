<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\WithPagination;

trait Datatable
{
    use WithPagination;

    public int $perPage = 25;

    public array $orderable;

    public array $filterable;

    #[Url(keep: true)]
    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public bool $selectPage = false;

    public string $sortBy = '';

    public string $sortDirection = '';

    protected $queryString = [
        'search',
        'sortBy',
        'sortDirection',
    ];

    public function mountDatatable(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->paginationOptions = [25, 50, 100];
        $this->orderable = (new $this->model())->orderable;
        $this->filterable = (new $this->model())->filterable;
    }

    public function sortingBy($field, $direction): void
    {
        if ($field !== $this->sortBy) {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
        $this->sortDirection = $direction;
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
