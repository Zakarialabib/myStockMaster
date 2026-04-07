<?php

declare(strict_types=1);

namespace App\Livewire;

trait WithSorting
{
    /** @var mixed */
    public $sortBy = 'id';

    /** @var mixed */
    public $sortDirection = 'desc';

    /**
     * @param mixed $field
     */
    public function sortBy(mixed $field): void
    {
        $this->sortBy = $field;

        $this->sortDirection = $this->sortBy === $field
            ? $this->reverseSort()
            : 'asc';
    }

    public function reverseSort(): string
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }
}
