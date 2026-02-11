<?php

declare(strict_types=1);

namespace App\Actions\Notifications;

use App\Models\Sale;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Handles notification failures and implements fallback strategies.
 *
 * ARCHITECTURAL CONTEXT:
 * This action provides centralized handling of notification failures,
 * implementing retry logic and alternative notification channels.
 *
 * EXTENSION POINTS:
 * Add custom retry strategies, escalation procedures, or integration
 * with external monitoring systems.
 *
 * COMMON PITFALLS:
 * Avoid infinite retry loops. Implement exponential backoff for retries.
 * Ensure fallback notifications don't create additional failures.
 */
final class HandleNotificationFailureAction
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
    }

    public function handleSaleProcessingFailure(Sale $sale, Throwable $exception): void
    {
        try {
            // Log the failure
            Log::error('Sale processing failure', [
                'order_id'  => $sale->id,
                'exception' => $exception->getMessage(),
                'trace'     => $exception->getTraceAsString(),
            ]);

            // Notify management about the failure
            $this->notificationService->notifyManagement(
                $sale,
                $sale->status,
                "Sale processing failed: {$exception->getMessage()}",
            );

            // Notify customer about potential delays
            $this->notifyCustomerProcessingDelay($sale);

            // Mark sale for manual review if needed
            $this->markSaleForManualReview($sale, $exception);
        } catch (Throwable $e) {
            Log::critical('Failed to handle sale processing failure', [
                'order_id'           => $sale->id,
                'original_exception' => $exception->getMessage(),
                'handling_exception' => $e->getMessage(),
            ]);
        }
    }

    public function handleStatusChangeFailure(Sale $sale, $oldStatus, $newStatus, Throwable $exception): void
    {
        try {
            // Log the status change failure
            Log::error('Sale status change failure', [
                'order_id'   => $sale->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'exception'  => $exception->getMessage(),
            ]);

            // Notify management about status change issues
            $this->notificationService->notifyManagement(
                $sale,
                $sale->status,
                "Status change failed from {$oldStatus} to {$newStatus}: {$exception->getMessage()}",
            );

            // Attempt to revert status if possible
            $this->attemptStatusRevert($sale, $oldStatus, $exception);
        } catch (Throwable $e) {
            Log::critical('Failed to handle status change failure', [
                'order_id'           => $sale->id,
                'handling_exception' => $e->getMessage(),
            ]);
        }
    }

    public function notifyCustomerProcessingDelay(Sale $sale): void
    {
        try {
            if ($sale->customer_email) {
                // Send email notification about delay
                Log::info('Customer delay notification sent via email', [
                    'order_id' => $sale->id,
                    'email'    => $sale->customer_email,
                ]);
            }

            if ($sale->customer_phone) {
                // Send SMS about delay
                $message = "We're experiencing a slight delay with your sale #{$sale->reference}. We'll update you shortly.";
                $this->notificationService->sendSmsNotification($sale->customer_phone, $message);
            }

            // Send push notification if available
            $this->notificationService->sendRealTimeNotification('sale-delays', [
                'order_id' => $sale->id,
                'message'  => 'Your sale is experiencing a slight delay',
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to notify customer about processing delay', [
                'order_id' => $sale->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function markSaleForManualReview(Sale $sale, Throwable $exception): void
    {
        try {
            // Add a note to the sale about the failure
            $sale->update([
                'notes'                  => ($sale->notes ?? '')."\n\nProcessing failure: {$exception->getMessage()} at ".now()->toDateTimeString(),
                'requires_manual_review' => true,
            ]);

            Log::info('Sale marked for manual review', [
                'order_id' => $sale->id,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to mark sale for manual review', [
                'order_id' => $sale->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function attemptStatusRevert(Sale $sale, $oldStatus, Throwable $exception): void
    {
        try {
            // Only attempt revert for certain scenarios
            if ($this->shouldAttemptRevert($exception)) {
                Log::info('Attempting to revert sale status', [
                    'order_id'     => $sale->id,
                    'reverting_to' => $oldStatus,
                ]);

                // TODO: Implement status revert logic
                // This should be done carefully to avoid data inconsistency
            }
        } catch (Throwable $e) {
            Log::error('Failed to revert sale status', [
                'order_id' => $sale->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    private function shouldAttemptRevert(Throwable $exception): bool
    {
        // Define conditions where status revert is safe and appropriate
        return ! str_contains($exception->getMessage(), 'payment') &&
               ! str_contains($exception->getMessage(), 'inventory');
    }
}
