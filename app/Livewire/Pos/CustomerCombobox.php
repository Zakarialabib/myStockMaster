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

    public int $highlightedIndex = -1;

    // public function updatedSearch(): void
    // {
    //     $this->highlightedIndex = -1;
    // }

    public function selectCustomer(int|string $customerId): void
    {
        $this->selectedCustomerId = $customerId;
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
        unset($this->customers, $this->selectedCustomerName);
    }

    #[Computed]
    public function customers(): Collection
    {
        $builder = Customer::query()->select(['id', 'name', 'phone']);

        if (strlen($this->search) >= 2) {
            $builder->where(function (\Illuminate\Contracts\Database\Query\Builder $builder): void {
                $builder->whereLike('name', '%' . $this->search . '%')
                    ->orWhereLike('phone', '%' . $this->search . '%');
            });
        }

        return $builder->limit(10)->get();
    }

    #[Computed]
    public function selectedCustomerName(): ?string
    {
        if ($this->selectedCustomerId) {
            return Customer::query()
                ->whereKey($this->selectedCustomerId)
                ->value('name');
        }

        return null;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.pos.customer-combobox');
    }
}
