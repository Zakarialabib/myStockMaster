<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Attributes\On;

trait ModalTrait
{
    public bool $showModal = false;

    public function openModal(): void
    {
        $this->showModal = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    #[On('closeModal')]
    public function handleCloseModal(): void
    {
        $this->closeModal();
    }
}
