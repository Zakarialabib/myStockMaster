<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ComponentDocumentation extends Component
{
    public $activeTab = 'overview';
    public $showModal = false;
    public $showDeleteModal = false;
    public $selectedItems = [];
    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedCategory = '';
    public $availability = '';
    public $seasonality = '';
    public $loading = false;

    // Sample data for demonstrations
    public $sampleUsers;
    public $sampleProducts;
    public $sampleCategories;
    public $sampleOrders;

    public function mount()
    {
        $this->initializeSampleData();
    }

    public function initializeSampleData()
    {
        // Sample users data
        $this->sampleUsers = collect([
            (object) ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active', 'role' => 'Admin', 'created_at' => now()->subDays(10)],
            (object) ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'active', 'role' => 'User', 'created_at' => now()->subDays(5)],
            (object) ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'inactive', 'role' => 'User', 'created_at' => now()->subDays(15)],
            (object) ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'status' => 'pending', 'role' => 'Manager', 'created_at' => now()->subDays(2)],
            (object) ['id' => 5, 'name' => 'Charlie Wilson', 'email' => 'charlie@example.com', 'status' => 'active', 'role' => 'User', 'created_at' => now()->subDays(7)],
        ]);

        // Sample products data
        $this->sampleProducts = collect([
            (object) ['id' => 1, 'name' => 'Wireless Headphones', 'sku' => 'WH-001', 'price' => 99.99, 'stock' => 25, 'category' => 'Electronics', 'status' => 'active', 'image' => null],
            (object) ['id' => 2, 'name' => 'Coffee Mug', 'sku' => 'CM-002', 'price' => 12.50, 'stock' => 150, 'category' => 'Kitchen', 'status' => 'active', 'image' => null],
            (object) ['id' => 3, 'name' => 'Laptop Stand', 'sku' => 'LS-003', 'price' => 45.00, 'stock' => 0, 'category' => 'Office', 'status' => 'out_of_stock', 'image' => null],
            (object) ['id' => 4, 'name' => 'Bluetooth Speaker', 'sku' => 'BS-004', 'price' => 75.99, 'stock' => 8, 'category' => 'Electronics', 'status' => 'low_stock', 'image' => null],
            (object) ['id' => 5, 'name' => 'Desk Organizer', 'sku' => 'DO-005', 'price' => 28.99, 'stock' => 42, 'category' => 'Office', 'status' => 'active', 'image' => null],
        ]);

        // Sample categories data
        $this->sampleCategories = collect([
            (object) ['id' => 1, 'name' => 'Electronics', 'products_count' => 15, 'status' => 'active', 'created_at' => now()->subDays(30)],
            (object) ['id' => 2, 'name' => 'Kitchen', 'products_count' => 8, 'status' => 'active', 'created_at' => now()->subDays(25)],
            (object) ['id' => 3, 'name' => 'Office', 'products_count' => 12, 'status' => 'active', 'created_at' => now()->subDays(20)],
            (object) ['id' => 4, 'name' => 'Clothing', 'products_count' => 0, 'status' => 'inactive', 'created_at' => now()->subDays(45)],
        ]);

        // Sample orders data
        $this->sampleOrders = collect([
            (object) ['id' => 1, 'order_number' => 'ORD-001', 'customer' => 'John Doe', 'total' => 199.98, 'status' => 'completed', 'created_at' => now()->subDays(3)],
            (object) ['id' => 2, 'order_number' => 'ORD-002', 'customer' => 'Jane Smith', 'total' => 75.99, 'status' => 'processing', 'created_at' => now()->subDays(1)],
            (object) ['id' => 3, 'order_number' => 'ORD-003', 'customer' => 'Bob Johnson', 'total' => 45.00, 'status' => 'pending', 'created_at' => now()],
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleModal()
    {
        $this->showModal = ! $this->showModal;
    }

    public function toggleDeleteModal()
    {
        $this->showDeleteModal = ! $this->showDeleteModal;
    }

    public function simulateLoading()
    {
        $this->loading = true;
        // Simulate async operation
        $this->dispatch('loading-complete');
    }

    public function editItem($id)
    {
        session()->flash('message', "Edit item with ID: {$id}");
    }

    public function deleteItem($id)
    {
        session()->flash('message', "Delete item with ID: {$id}");
    }

    public function viewItem($id)
    {
        session()->flash('message', "View item with ID: {$id}");
    }

    public function bulkDelete()
    {
        if (count($this->selectedItems) > 0) {
            session()->flash('message', 'Bulk delete '.count($this->selectedItems).' items');
            $this->selectedItems = [];
        }
    }

    public function exportData()
    {
        session()->flash('message', 'Export functionality triggered');
    }

    public function refreshData()
    {
        $this->initializeSampleData();
        session()->flash('message', 'Data refreshed successfully');
    }

    public function render()
    {
        return view('livewire.component-documentation');
    }
}
