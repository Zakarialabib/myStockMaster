<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Warehouse;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WarehouseForm extends Form
{
    public ?Warehouse $warehouse = null;

    #[Validate('string|required|max:255')]
    public string $name = '';

    #[Validate('numeric|nullable|max:255')]
    public ?string $phone = null;

    #[Validate('nullable|max:255')]
    public ?string $country = null;

    #[Validate('nullable|max:255')]
    public ?string $city = null;

    #[Validate('nullable|max:255')]
    public ?string $email = null;

    public function setWarehouse(Warehouse $warehouse): void
    {
        $this->warehouse = $warehouse;
        $this->name = $warehouse->name;
        $this->phone = $warehouse->phone;
        $this->country = $warehouse->country;
        $this->city = $warehouse->city;
        $this->email = $warehouse->email;
    }

    public function store(): void
    {
        $this->validate();

        Warehouse::create($this->except('warehouse'));
    }

    public function update(): void
    {
        $this->validate();

        $this->warehouse->update($this->except('warehouse'));
    }
}
