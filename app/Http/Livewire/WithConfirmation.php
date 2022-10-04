<?php

namespace App\Http\Livewire;

trait WithConfirmation
{
    public function confirm($callback, ...$argv)
    {
        $this->emit('confirm', compact('callback', 'argv'));
    }
}