<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $href;
    public $icon;
    public $iconPosition;
    public $size;
    public $variant;
    public $outline;

    public function __construct(
        $type = null,
        $href = '#',
        $icon = null,
        $iconPosition = 'left',
        $size = 'md',
        $variant = 'primary',
        $outline = false,
        // Legacy boolean props
        $primary = false,
        $secondary = false,
        $info = false,
        $alert = false,
        $success = false,
        $danger = false,
        $warning = false,
        $primaryOutline = false,
        $secondaryOutline = false,
        $infoOutline = false,
        $successOutline = false,
        $alertOutline = false,
        $dangerOutline = false,
        $warningOutline = false
    ) {
        $this->type = $type;
        $this->href = $href;
        $this->icon = $icon;
        $this->iconPosition = $iconPosition;
        $this->size = $size;

        // Determine outline
        $this->outline = $outline ||
            $primaryOutline || $secondaryOutline || $infoOutline ||
            $successOutline || $dangerOutline || $warningOutline || $alertOutline;

        // Determine variant
        if ($primary || $primaryOutline) {
            $this->variant = 'primary';
        } elseif ($secondary || $secondaryOutline) {
            $this->variant = 'secondary';
        } elseif ($info || $infoOutline) {
            $this->variant = 'info';
        } elseif ($success || $successOutline) {
            $this->variant = 'success';
        } elseif ($danger || $dangerOutline) {
            $this->variant = 'danger';
        } elseif ($warning || $warningOutline) {
            $this->variant = 'warning';
        } elseif ($alert || $alertOutline) {
            $this->variant = 'alert';
        } else {
            $this->variant = $variant;
        }
    }

    public function getClasses()
    {
        $sizeClasses = [
            'xs' => 'px-2.5 py-1.5 text-xs',
            'sm' => 'px-3 py-2 text-sm',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-4 py-2 text-base',
            'xl' => 'px-6 py-3 text-base',
        ];

        $variantClasses = [
            'primary' => $this->outline
                ? 'border-primary-600 text-primary-600 hover:bg-primary-50 dark:border-primary-400 dark:text-primary-400 dark:hover:bg-primary-900/20'
                : 'bg-gradient-to-br from-primary-600 to-primary-500 text-white shadow-lg hover:brightness-110 dark:from-primary-500 dark:to-primary-400',
            'secondary' => $this->outline
                ? 'border-secondary-600 text-secondary-600 hover:bg-secondary-50 dark:border-secondary-400 dark:text-secondary-400 dark:hover:bg-secondary-900/20'
                : 'bg-gray-400 text-gray-900 hover:bg-gray-800 hover:text-white dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600',
            'info' => $this->outline
                ? 'border-cyan-600 text-cyan-600 hover:bg-cyan-50 dark:border-cyan-400 dark:text-cyan-400 dark:hover:bg-cyan-900/20'
                : 'bg-cyan-600 text-white hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-600',
            'success' => $this->outline
                ? 'border-success-600 text-success-600 hover:bg-success-50 dark:border-success-400 dark:text-success-400 dark:hover:bg-success-900/20'
                : 'bg-success-600 text-white hover:bg-success-700 dark:bg-success-500 dark:hover:bg-success-600',
            'danger' => $this->outline
                ? 'border-error-600 text-error-600 hover:bg-error-50 dark:border-error-400 dark:text-error-400 dark:hover:bg-error-900/20'
                : 'bg-error-600 text-white hover:bg-error-700 dark:bg-error-500 dark:hover:bg-error-600',
            'warning' => $this->outline
                ? 'border-warning-600 text-warning-600 hover:bg-warning-50 dark:border-warning-400 dark:text-warning-400 dark:hover:bg-warning-900/20'
                : 'bg-warning-600 text-white hover:bg-warning-700 dark:bg-warning-500 dark:hover:bg-warning-600',
            'alert' => $this->outline
                ? 'border-orange-600 text-orange-600 hover:bg-orange-50 dark:border-orange-400 dark:text-orange-400 dark:hover:bg-orange-900/20'
                : 'bg-orange-600 text-white hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600',
        ];

        $baseClasses = 'inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
        $borderClass = $this->outline ? 'border-2 bg-transparent' : 'border border-transparent';

        return $baseClasses.' '.$borderClass.' '.
               ($sizeClasses[$this->size] ?? $sizeClasses['md']).' '.
               ($variantClasses[$this->variant] ?? $variantClasses['primary']);
    }

    public function render()
    {
        return view('components.button');
    }
}
