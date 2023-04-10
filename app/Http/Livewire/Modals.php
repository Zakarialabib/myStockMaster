<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;

class Modals extends Component
{
    public array $components = [];

    protected $listeners = [
        'openModal',
        'closeModal',
        'closePreviousModal',
    ];

    public function openModal($componentName, $attributes = [])
    {
        $componentClass = app('livewire')->getClass($componentName);
        $requiredInterface = \App\Http\Livewire\ModalComponent::class;

        if ( ! is_subclass_of($componentClass, $requiredInterface)) {
            throw new Exception("[{$componentClass}] does not implement [{$requiredInterface}] interface.");
        }

        $this->components[] = [
            'component'  => $componentName,
            'maxWidth'   => $componentClass::modalMaxWidth,
            'attributes' => $attributes,
        ];
    }

    public function closeModal($componentName)
    {
        $this->components = array_filter(
            $this->components,
            fn ($item) => $item['component'] !== $componentName
        );
    }

    public function closePreviousModal()
    {
        $index = count($this->components) - 2;

        if ($index < 0) {
            return;
        }
        $modalKey = $this->components[$index]['component'];

        if ($modalKey) {
            $this->closeModal($modalKey);
        }
    }

    public function render()
    {
        return view('livewire.modals');
    }
}
