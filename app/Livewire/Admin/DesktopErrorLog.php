<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Native\Services\DesktopErrorHandler;
use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DesktopErrorLog extends Component
{
    use WithPagination;

    public $showStatistics = true;

    public $selectedError = null;

    public $filterSeverity = '';

    public $filterCategory = '';

    public $filterDateFrom = '';

    public $filterDateTo = '';

    public $searchTerm = '';

    protected $errorHandler;

    public function boot()
    {
        $this->errorHandler = app(DesktopErrorHandler::class);
    }

    public function mount()
    {
        // Only allow access in desktop mode
        if (! $this->isDesktopMode()) {
            abort(404);
        }
    }

    public function render()
    {
        $userId = Auth::id();
        $statistics = $this->errorHandler->getErrorStatistics($userId);
        $errorHistory = $this->getFilteredErrors();

        return view('livewire.admin.desktop-error-log', [
            'statistics' => $statistics,
            'errorHistory' => $errorHistory,
            'severityOptions' => $this->getSeverityOptions(),
            'categoryOptions' => $this->getCategoryOptions($statistics),
        ])->layout('layouts.app');
    }

    public function toggleStatistics()
    {
        $this->showStatistics = ! $this->showStatistics;
    }

    public function viewErrorDetails($errorId)
    {
        $this->selectedError = $this->errorHandler->getErrorDetails($errorId);
        $this->dispatch('show-error-modal');
    }

    public function closeErrorDetails()
    {
        $this->selectedError = null;
        $this->dispatch('hide-error-modal');
    }

    public function clearErrorHistory()
    {
        $userId = Auth::id();

        if ($this->errorHandler->clearErrorHistory($userId)) {
            session()->flash('success', __('desktop.logging.error_history') . ' cleared successfully.');
            $this->resetPage();
        } else {
            session()->flash('error', 'Failed to clear error history.');
        }
    }

    public function resetFilters()
    {
        $this->filterSeverity = '';
        $this->filterCategory = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function updatedFilterSeverity()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function exportErrorLog()
    {
        $userId = Auth::id();
        $errors = $this->errorHandler->getErrorHistory($userId, 1000);

        $filename = 'desktop_error_log_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->streamDownload(function () use ($errors) {
            echo json_encode($errors, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    private function getFilteredErrors(): array
    {
        $userId = Auth::id();
        $allErrors = $this->errorHandler->getErrorHistory($userId, 1000);

        $filtered = array_filter($allErrors, function ($error) {
            // Filter by severity
            if ($this->filterSeverity && ($error['severity'] ?? '') !== $this->filterSeverity) {
                return false;
            }

            // Filter by category
            if ($this->filterCategory && ($error['category'] ?? '') !== $this->filterCategory) {
                return false;
            }

            // Filter by date range
            if ($this->filterDateFrom || $this->filterDateTo) {
                $errorDate = \Carbon\Carbon::parse($error['timestamp']);

                if ($this->filterDateFrom && $errorDate->lt(\Carbon\Carbon::parse($this->filterDateFrom))) {
                    return false;
                }

                if ($this->filterDateTo && $errorDate->gt(\Carbon\Carbon::parse($this->filterDateTo)->endOfDay())) {
                    return false;
                }
            }

            // Filter by search term
            if ($this->searchTerm) {
                $searchLower = strtolower($this->searchTerm);
                $message = strtolower($error['message'] ?? '');
                $file = strtolower($error['file'] ?? '');

                if (! str_contains($message, $searchLower) && ! str_contains($file, $searchLower)) {
                    return false;
                }
            }

            return true;
        });

        // Sort by timestamp (newest first)
        usort($filtered, function ($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return $filtered;
    }

    private function getSeverityOptions(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ];
    }

    private function getCategoryOptions(array $statistics): array
    {
        $categories = array_keys($statistics['by_category'] ?? []);

        return array_combine($categories, array_map('ucfirst', $categories));
    }

    private function isDesktopMode(): bool
    {
        return EnvironmentService::isDesktop();
    }

    public function getSeverityColor(string $severity): string
    {
        return match ($severity) {
            'critical' => 'text-red-600 bg-red-100',
            'high' => 'text-red-500 bg-red-50',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'low' => 'text-blue-600 bg-blue-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getCategoryIcon(string $category): string
    {
        return match ($category) {
            'database' => 'database',
            'network' => 'wifi',
            'validation' => 'exclamation-triangle',
            'permission' => 'lock',
            'filesystem' => 'folder',
            'javascript' => 'code',
            default => 'exclamation-circle'
        };
    }

    public function formatTimestamp(string $timestamp): string
    {
        return \Carbon\Carbon::parse($timestamp)->format('M j, Y g:i A');
    }

    public function getRelativeTime(string $timestamp): string
    {
        return \Carbon\Carbon::parse($timestamp)->diffForHumans();
    }
}
