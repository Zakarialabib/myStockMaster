<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Component;

class CustomerCombobox extends Component
{
    public string $search = '';

    public int|string|null $selectedCustomerId = null;

    public bool $isOpen = false;

    public int $highlightedIndex = -1;

    public function mount(?int $customerId = null): void
    {
        $this->selectedCustomerId = $customerId;
    }

    public function updatedSearch(): void
    {
        $this->isOpen = true;
        $this->highlightedIndex = -1;
    }

    public function selectCustomer(int $customerId): void
    {
        $this->selectedCustomerId = $customerId;
        $this->isOpen = false;
        $this->search = '';
        $this->highlightedIndex = -1;
        $this->dispatch('customer-selected', customerId: $customerId);
    }

    public function clearSelection(): void
    {
        $this->selectedCustomerId = null;
        $this->dispatch('customer-selected', customerId: null);
    }

    public function highlightNext(): void
    {
        $customers = $this->getCustomers();
        if ($this->highlightedIndex < count($customers) - 1) {
            $this->highlightedIndex++;
        }
    }

    public function highlightPrev(): void
    {
        if ($this->highlightedIndex > 0) {
            $this->highlightedIndex--;
        }
    }

    public function selectHighlighted(): void
    {
        $customers = $this->getCustomers();
        if ($this->highlightedIndex >= 0 && isset($customers[$this->highlightedIndex])) {
            $this->selectCustomer($customers[$this->highlightedIndex]->id);
        }
    }

    #[On('customer-created')]
    public function refreshCustomers(): void
    {
        $this->dispatch('$refresh');
    }

    public function getCustomers()
    {
        $query = Customer::select(['id', 'name', 'phone']);

        if (strlen($this->search) >= 2) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        return $query->limit(10)->get();
    }

    public function getSelectedCustomerName(): ?string
    {
        if ($this->selectedCustomerId) {
            $customer = Customer::find($this->selectedCustomerId);

            return $customer?->name;
        }

        return null;
    }

    public function render()
    {
        return view('livewire.pos.customer-combobox', [
            'customers' => $this->getCustomers(),
            'selectedCustomerName' => $this->getSelectedCustomerName(),
        ]);
    }
}
