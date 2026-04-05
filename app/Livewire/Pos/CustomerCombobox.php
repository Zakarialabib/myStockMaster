<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

class CustomerCombobox extends Component
{
    public string $search = '';

    #[Modelable]
    public int|string|null $selectedCustomerId = null;

    public bool $isOpen = false;

    public int $highlightedIndex = -1;

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
    }

    public function clearSelection(): void
    {
        $this->selectedCustomerId = null;
    }

    public function highlightNext(): void
    {
        if ($this->highlightedIndex < $this->customers->count() - 1) {
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
        if ($this->highlightedIndex >= 0 && isset($this->customers[$this->highlightedIndex])) {
            $this->selectCustomer($this->customers[$this->highlightedIndex]->id);
        }
    }

    #[On('customer-created')]
    public function refreshCustomers(): void
    {
        unset($this->customers);
    }

    #[Computed]
    public function customers(): Collection
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

    #[Computed]
    public function selectedCustomerName(): ?string
    {
        if ($this->selectedCustomerId) {
            return Customer::find($this->selectedCustomerId)?->name;
        }

        return null;
    }

    public function render()
    {
        return view('livewire.pos.customer-combobox');
    }
}
