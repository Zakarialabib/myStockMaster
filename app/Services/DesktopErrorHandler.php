<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DesktopErrorHandler
{
    private const ERROR_CACHE_PREFIX = 'desktop_error_';
    private const ERROR_CACHE_TTL = 3600; // 1 hour
    private const MAX_ERROR_HISTORY = 50;

    /**
     * Handle desktop-specific errors
     */
    public function handleError(Throwable $error, array $context = []): array
    {
        $errorId = $this->generateErrorId();
        $userId = Auth::id();
        
        $errorData = [
            'id' => $errorId,
            'message' => $error->getMessage(),
            'code' => $error->getCode(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTraceAsString(),
            'context' => $context,
            'user_id' => $userId,
            'timestamp' => now()->toISOString(),
            'severity' => $this->determineSeverity($error),
            'category' => $this->categorizeError($error),
            'desktop_specific' => true
        ];

        // Log the error
        $this->logError($errorData);

        // Store error for desktop app retrieval
        $this->storeErrorForDesktop($errorId, $errorData);

        // Add to error history
        if ($userId) {
            $this->addToErrorHistory($userId, $errorData);
        }

        // Determine user notification
        $notification = $this->createUserNotification($errorData);

        return [
            'error_id' => $errorId,
            'severity' => $errorData['severity'],
            'category' => $errorData['category'],
            'user_message' => $notification['message'],
            'show_notification' => $notification['show'],
            'notification_type' => $notification['type'],
            'recovery_suggestions' => $this->getRecoverySuggestions($error),
            'should_report' => $this->shouldReportError($error)
        ];
    }

    /**
     * Handle PHP errors
     */
    public function handlePhpError(int $severity, string $message, string $file, int $line): void
    {
        $error = new \ErrorException($message, 0, $severity, $file, $line);
        $this->handleError($error, [
            'type' => 'php_error',
            'severity_level' => $severity
        ]);
    }

    /**
     * Handle exceptions
     */
    public function handleException(Throwable $exception): void
    {
        $this->handleError($exception, [
            'type' => 'exception'
        ]);
    }

    /**
     * Handle JavaScript errors from desktop app
     */
    public function handleJavaScriptError(array $errorData): array
    {
        $errorId = $this->generateErrorId();
        $userId = Auth::id();

        $processedError = [
            'id' => $errorId,
            'message' => $errorData['message'] ?? 'Unknown JavaScript error',
            'source' => $errorData['source'] ?? 'unknown',
            'line' => $errorData['line'] ?? 0,
            'column' => $errorData['column'] ?? 0,
            'stack' => $errorData['stack'] ?? '',
            'user_agent' => $errorData['userAgent'] ?? '',
            'url' => $errorData['url'] ?? '',
            'user_id' => $userId,
            'timestamp' => now()->toISOString(),
            'severity' => $this->determineJsSeverity($errorData),
            'category' => 'javascript',
            'desktop_specific' => true
        ];

        // Log JavaScript error
        Log::channel('desktop')->error('Desktop JavaScript Error', $processedError);

        // Store for desktop app
        $this->storeErrorForDesktop($errorId, $processedError);

        // Add to history
        $this->addToErrorHistory($userId, $processedError);

        return [
            'error_id' => $errorId,
            'severity' => $processedError['severity'],
            'logged' => true,
            'user_message' => $this->getJsErrorMessage($processedError)
        ];
    }

    /**
     * Get error history for user
     */
    public function getErrorHistory(int|string $userId, int $limit = 20): array
    {
        $cacheKey = "desktop_error_history_{$userId}";
        $history = Cache::get($cacheKey, []);

        return array_slice($history, -$limit);
    }

    /**
     * Get error details by ID
     */
    public function getErrorDetails(string $errorId): ?array
    {
        $cacheKey = self::ERROR_CACHE_PREFIX . $errorId;
        return Cache::get($cacheKey);
    }

    /**
     * Clear error history for user
     */
    public function clearErrorHistory(int|string $userId): bool
    {
        $cacheKey = "desktop_error_history_{$userId}";
        return Cache::forget($cacheKey);
    }

    /**
     * Get error statistics
     */
    public function getErrorStatistics(int|string $userId): array
    {
        $history = $this->getErrorHistory($userId, self::MAX_ERROR_HISTORY);
        
        $stats = [
            'total_errors' => count($history),
            'errors_today' => 0,
            'errors_this_week' => 0,
            'by_severity' => ['low' => 0, 'medium' => 0, 'high' => 0, 'critical' => 0],
            'by_category' => [],
            'most_recent' => null,
            'most_frequent' => null
        ];

        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();
        $categoryCount = [];
        $messageCount = [];

        foreach ($history as $error) {
            $errorTime = \Carbon\Carbon::parse($error['timestamp']);
            
            // Count by time
            if ($errorTime->gte($today)) {
                $stats['errors_today']++;
            }
            if ($errorTime->gte($weekStart)) {
                $stats['errors_this_week']++;
            }

            // Count by severity
            $severity = $error['severity'] ?? 'medium';
            $stats['by_severity'][$severity]++;

            // Count by category
            $category = $error['category'] ?? 'unknown';
            $categoryCount[$category] = ($categoryCount[$category] ?? 0) + 1;

            // Count by message for most frequent
            $message = $error['message'] ?? 'Unknown error';
            $messageCount[$message] = ($messageCount[$message] ?? 0) + 1;

            // Most recent
            if (!$stats['most_recent'] || $errorTime->gt(\Carbon\Carbon::parse($stats['most_recent']['timestamp']))) {
                $stats['most_recent'] = $error;
            }
        }

        $stats['by_category'] = $categoryCount;
        
        // Find most frequent error
        if (!empty($messageCount)) {
            arsort($messageCount);
            $mostFrequentMessage = array_key_first($messageCount);
            $stats['most_frequent'] = [
                'message' => $mostFrequentMessage,
                'count' => $messageCount[$mostFrequentMessage]
            ];
        }

        return $stats;
    }

    /**
     * Generate unique error ID
     */
    private function generateErrorId(): string
    {
        return 'desktop_' . uniqid() . '_' . time();
    }

    /**
     * Determine error severity
     */
    private function determineSeverity(Throwable $error): string
    {
        $message = strtolower($error->getMessage());
        $class = get_class($error);

        // Critical errors
        if (str_contains($message, 'fatal') || 
            str_contains($message, 'memory') ||
            str_contains($class, 'FatalError')) {
            return 'critical';
        }

        // High severity
        if (str_contains($message, 'database') ||
            str_contains($message, 'connection') ||
            str_contains($message, 'authentication') ||
            str_contains($class, 'PDOException')) {
            return 'high';
        }

        // Medium severity
        if (str_contains($message, 'validation') ||
            str_contains($message, 'permission') ||
            str_contains($message, 'not found')) {
            return 'medium';
        }

        // Default to low
        return 'low';
    }

    /**
     * Categorize error type
     */
    private function categorizeError(Throwable $error): string
    {
        $message = strtolower($error->getMessage());
        $class = get_class($error);

        if (str_contains($class, 'PDO') || str_contains($message, 'database')) {
            return 'database';
        }

        if (str_contains($message, 'network') || str_contains($message, 'connection')) {
            return 'network';
        }

        if (str_contains($message, 'validation') || str_contains($message, 'invalid')) {
            return 'validation';
        }

        if (str_contains($message, 'permission') || str_contains($message, 'unauthorized')) {
            return 'permission';
        }

        if (str_contains($message, 'file') || str_contains($message, 'directory')) {
            return 'filesystem';
        }

        return 'application';
    }

    /**
     * Determine JavaScript error severity
     */
    private function determineJsSeverity(array $errorData): string
    {
        $message = strtolower($errorData['message'] ?? '');

        if (str_contains($message, 'uncaught') || str_contains($message, 'fatal')) {
            return 'high';
        }

        if (str_contains($message, 'warning') || str_contains($message, 'deprecated')) {
            return 'low';
        }

        return 'medium';
    }

    /**
     * Log error with appropriate channel
     */
    private function logError(array $errorData): void
    {
        $logLevel = match ($errorData['severity']) {
            'critical' => 'critical',
            'high' => 'error',
            'medium' => 'warning',
            'low' => 'info',
            default => 'error'
        };

        Log::channel('desktop')->{$logLevel}('Desktop Application Error', $errorData);
    }

    /**
     * Store error for desktop app retrieval
     */
    private function storeErrorForDesktop(string $errorId, array $errorData): void
    {
        $cacheKey = self::ERROR_CACHE_PREFIX . $errorId;
        Cache::put($cacheKey, $errorData, self::ERROR_CACHE_TTL);
    }

    /**
     * Add error to user's error history
     */
    private function addToErrorHistory(int|string $userId, array $errorData): void
    {
        $cacheKey = "desktop_error_history_{$userId}";
        $history = Cache::get($cacheKey, []);
        
        $history[] = $errorData;
        
        // Keep only the last MAX_ERROR_HISTORY errors
        if (count($history) > self::MAX_ERROR_HISTORY) {
            $history = array_slice($history, -self::MAX_ERROR_HISTORY);
        }
        
        Cache::put($cacheKey, $history, now()->addDays(7));
    }

    /**
     * Create user notification for error
     */
    private function createUserNotification(array $errorData): array
    {
        $severity = $errorData['severity'];
        $category = $errorData['category'];

        $showNotification = in_array($severity, ['high', 'critical']);
        
        $message = match ($severity) {
            'critical' => __('desktop.errors.critical_error_occurred'),
            'high' => __('desktop.errors.error_occurred'),
            'medium' => __('desktop.errors.warning_occurred'),
            'low' => __('desktop.errors.info_message'),
            default => __('desktop.errors.unknown_error')
        };

        $type = match ($severity) {
            'critical' => 'error',
            'high' => 'error',
            'medium' => 'warning',
            'low' => 'info',
            default => 'error'
        };

        return [
            'show' => $showNotification,
            'message' => $message,
            'type' => $type
        ];
    }

    /**
     * Get recovery suggestions for error
     */
    private function getRecoverySuggestions(Throwable $error): array
    {
        $category = $this->categorizeError($error);
        
        return match ($category) {
            'database' => [
                __('desktop.errors.suggestions.check_database_connection'),
                __('desktop.errors.suggestions.restart_application'),
                __('desktop.errors.suggestions.contact_support')
            ],
            'network' => [
                __('desktop.errors.suggestions.check_internet_connection'),
                __('desktop.errors.suggestions.try_again_later'),
                __('desktop.errors.suggestions.switch_to_offline_mode')
            ],
            'validation' => [
                __('desktop.errors.suggestions.check_input_data'),
                __('desktop.errors.suggestions.review_form_fields')
            ],
            'permission' => [
                __('desktop.errors.suggestions.check_user_permissions'),
                __('desktop.errors.suggestions.contact_administrator')
            ],
            'filesystem' => [
                __('desktop.errors.suggestions.check_file_permissions'),
                __('desktop.errors.suggestions.ensure_disk_space')
            ],
            default => [
                __('desktop.errors.suggestions.restart_application'),
                __('desktop.errors.suggestions.contact_support')
            ]
        };
    }

    /**
     * Determine if error should be reported to external service
     */
    private function shouldReportError(Throwable $error): bool
    {
        $severity = $this->determineSeverity($error);
        return in_array($severity, ['high', 'critical']);
    }

    /**
     * Get user-friendly JavaScript error message
     */
    private function getJsErrorMessage(array $errorData): string
    {
        $severity = $errorData['severity'];
        
        return match ($severity) {
            'high' => __('desktop.errors.js_error_high'),
            'medium' => __('desktop.errors.js_error_medium'),
            'low' => __('desktop.errors.js_error_low'),
            default => __('desktop.errors.js_error_unknown')
        };
    }
}
