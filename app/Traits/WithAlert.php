<?php

declare(strict_types=1);

namespace App\Traits;

trait WithAlert
{
    /**
     * Dispatch an alert event to the frontend
     *
     * @param string $type The alert type (success, error, warning, info, danger)
     * @param string $message The alert message
     * @param array $options Additional options for the alert
     */
    public function alert(string $type, string $message, array $options = []): void
    {
        // Map 'danger' to 'error' for consistency
        if ($type === 'danger') {
            $type = 'error';
        }

        $this->dispatch('alert', [
            'type'    => $type,
            'message' => $message,
            'options' => $options,
        ]);
    }

    /** Dispatch a success alert */
    public function success(string $message, array $options = []): void
    {
        $this->alert('success', $message, $options);
    }

    /** Dispatch an error alert */
    public function error(string $message, array $options = []): void
    {
        $this->alert('error', $message, $options);
    }

    /** Dispatch a warning alert */
    public function warning(string $message, array $options = []): void
    {
        $this->alert('warning', $message, $options);
    }

    /** Dispatch an info alert */
    public function info(string $message, array $options = []): void
    {
        $this->alert('info', $message, $options);
    }
}
