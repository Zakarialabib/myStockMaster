<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(public $id = null, public $name = 'modal', public $show = false, public $maxWidth = '2xl', public $closeable = true, public $focusable = true, public $persistent = false, public $backdrop = true, public $animation = 'fade', public $zIndex = 'z-50', public $closeOnEscape = true, public $trapFocus = true, public $restoreScroll = true, public $lazy = false, public $cacheContent = false)
    {
    }

    public function maxWidthClass(): string
    {
        return [
            'xs' => 'sm:max-w-xs',
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            'full' => 'sm:max-w-full',
        ][$this->maxWidth] ?? 'sm:max-w-2xl';
    }

    public function animationClasses(): string
    {
        return [
            'fade' => 'transition-opacity duration-300',
            'slide' => 'transition-transform duration-300',
            'zoom' => 'transition-all duration-300',
            'none' => '',
        ][$this->animation] ?? 'transition-opacity duration-300';
    }

    public function render()
    {
        return view('components.modal');
    }
}
