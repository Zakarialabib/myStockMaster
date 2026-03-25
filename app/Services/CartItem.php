<?php

declare(strict_types=1);

namespace App\Services;

class CartItem
{
    protected array $data;

    protected string $rowId;

    public function __construct(array $data, string $rowId)
    {
        $this->data = $data;
        $this->rowId = $rowId;
    }

    public function __get(string $name)
    {
        return match ($name) {
            'id' => $this->data['id'],
            'name' => $this->data['name'],
            'price' => $this->data['price'],
            'qty', 'quantity' => $this->data['quantity'],
            'rowId' => $this->rowId,
            'options' => new CartItemOptions($this->data['attributes'] ?? []),
            'attributes' => $this->data['attributes'] ?? [],
            default => $this->data[$name] ?? null,
        };
    }

    public function __isset(string $name): bool
    {
        return match ($name) {
            'id', 'name', 'price', 'qty', 'quantity', 'rowId', 'options', 'attributes' => true,
            default => isset($this->data[$name]),
        };
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getRowId(): string
    {
        return $this->rowId;
    }
}

class CartItemOptions
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
