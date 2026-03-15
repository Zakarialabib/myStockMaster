<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $name;
    public $show;
    public $maxWidth;
    public $closeable;
    public $focusable;
    public $persistent;
    public $backdrop;
    public $animation;
    public $zIndex;
    public $closeOnEscape;
    public $trapFocus;
    public $restoreScroll;
    public $lazy;
    public $cacheContent;

    public function __construct(
        $id = null,
        $name = 'modal',
        $show = false,
        $maxWidth = '2xl',
        $closeable = true,
        $focusable = true,
        $persistent = false,
        $backdrop = true,
        $animation = 'fade',
        $zIndex = 'z-50',
        $closeOnEscape = true,
        $trapFocus = true,
        $restoreScroll = true,
        $lazy = false,
        $cacheContent = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->show = $show;
        $this->maxWidth = $maxWidth;
        $this->closeable = $closeable;
        $this->focusable = $focusable;
        $this->persistent = $persistent;
        $this->backdrop = $backdrop;
        $this->animation = $animation;
        $this->zIndex = $zIndex;
        $this->closeOnEscape = $closeOnEscape;
        $this->trapFocus = $trapFocus;
        $this->restoreScroll = $restoreScroll;
        $this->lazy = $lazy;
        $this->cacheContent = $cacheContent;
    }

    public function maxWidthClass()
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

    public function animationClasses()
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
