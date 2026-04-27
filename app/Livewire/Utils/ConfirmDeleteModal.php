<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Attributes\Isolate;
use Livewire\Attributes\On;
use Livewire\Component;

#[Isolate]
class ConfirmDeleteModal extends Component
{
    public bool $show = false;

    public string $title = 'Confirm Delete';

    public string $message = 'Are you sure you want to delete this item?';

    public string $confirmText = 'Delete';

    public string $cancelText = 'Cancel';

    public ?string $itemId = null;

    #[On('openDeleteModal')]
    public function openModal(array $data = []): void
    {
        $this->show = true;
        $this->title = $data['title'] ?? $this->title;
        $this->message = $data['message'] ?? $this->message;
        $this->itemId = $data['itemId'] ?? null;
    }

    public function confirm(): void
    {
        $this->dispatch('deleteConfirmed', itemId: $this->itemId);
        $this->closeModal();
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->reset(['itemId']);
    }

    public function render()
    {
        return view('livewire.utils.confirm-delete-modal');
    }
}
