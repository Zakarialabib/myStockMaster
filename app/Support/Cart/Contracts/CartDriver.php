<?php

declare(strict_types=1);

namespace App\Support\Cart\Contracts;

use Illuminate\Support\Collection;

interface CartDriver
{
    public function get(string $instance): Collection;

    public function put(string $instance, Collection $content): void;

    public function forget(string $instance): void;
}
