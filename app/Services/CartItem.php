<?php

declare(strict_types=1);

namespace App\Services;

class CartItem
{
    public function __construct(protected array $data, protected string $rowId)
    {
    }

    public function __get(string $name): mixed
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
    public function __construct(protected array $data)
    {
    }

    public function __get(string $name): mixed
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
