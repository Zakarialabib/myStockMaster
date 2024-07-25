<?php

declare(strict_types=1);

namespace App\Http\Livewire;

trait WithSorting
{
    /** @var mixed */
    public $sortBy = 'id';

    /** @var mixed */
    public $sortDirection = 'desc';

    /**
     * @param mixed $field
     *
     * @return void
     */
    public function sortBy($field)
    {
        $this->sortBy = $field;

        $this->sortDirection = $this->sortBy === $field
            ? $this->reverseSort()
            : 'asc';
    }

    /** @return string */
    public function reverseSort()
    {
        return $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
    }
}
