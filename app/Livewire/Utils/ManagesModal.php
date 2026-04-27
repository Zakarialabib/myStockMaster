<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

trait ManagesModal
{
    /** @var array<string, bool> */
    public array $modals = [];

    /**
     * Generic method to open a modal by its identifier.
     * Optionally pass parameters to hydrate the component.
     */
    public function openModal(string $modalId, array $params = []): void
    {
        $this->modals[$modalId] = true;

        if (method_exists($this, 'hydrateModalParams')) {
            $this->hydrateModalParams($modalId, $params);
        }
    }

    /**
     * Generic method to close a modal by its identifier.
     */
    public function closeModal(string $modalId): void
    {
        $this->modals[$modalId] = false;
        $this->resetModal();
    }

    /**
     * Reset the modal state, including validation errors and form objects.
     */
    protected function resetModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        
        if (method_exists($this, 'resetForm')) {
            $this->resetForm();
        }
    }
}
