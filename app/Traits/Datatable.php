<?php

declare(strict_types=1);

namespace App\Traits;

trait Datatable
{
    public int $perPage;

    public $orderable = [];

    public string $search = '';

    public $selected = [];

    public $paginationOptions = [];

    public bool $refreshIndex;

    public bool $selectPage = false;

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }
}
