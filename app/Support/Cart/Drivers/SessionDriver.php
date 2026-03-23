<?php

declare(strict_types=1);

namespace App\Support\Cart\Drivers;

use App\Support\Cart\Contracts\CartDriver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

final class SessionDriver implements CartDriver
{
    private string $prefix = 'cart_';

    public function get(string $instance): Collection
    {
        return collect(Session::get($this->prefix.$instance, []));
    }

    public function put(string $instance, Collection $content): void
    {
        Session::put($this->prefix.$instance, $content->toArray());
    }

    public function forget(string $instance): void
    {
        Session::forget($this->prefix.$instance);
    }
}
