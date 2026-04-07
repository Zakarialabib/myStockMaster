<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\WithAlert;
use Livewire\Component;

class Calculator extends Component
{
    use WithAlert;

    public $number1 = '';

    public $number2 = '';

    public string $action = '+';

    public float $result = 0;

    public bool $disabled = true;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.calculator');
    }

    public function calculate(): void
    {
        $num1 = (float) $this->number1;
        $num2 = (float) $this->number2;

        if ($this->action === '-') {
            $this->result = $num1 - $num2;
        } elseif ($this->action === '+') {
            $this->result = $num1 + $num2;
        } elseif ($this->action === '*') {
            $this->result = $num1 * $num2;
        } elseif ($this->action === '/') {
            $this->result = $num1 / $num2;
        } elseif ($this->action === '%') {
            $this->result = $num1 / 100 * $num2;
        }
    }

    public function updated(mixed $property): void
    {
        $this->disabled = $this->number1 === '' || $this->number2 === '';
    }
}
