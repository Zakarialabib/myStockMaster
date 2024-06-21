<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;

class Calculator extends Component
{
    public $number1 = '';

    public $number2 = '';

    public string $action = '+';

    public float $result = 0;

    public bool $disabled = true;

    public function render()
    {
        return view('livewire.utils.calculator');
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

    public function updated($property): void
    {
        $this->disabled = $this->number1 === '' || $this->number2 === '';
    }
}
